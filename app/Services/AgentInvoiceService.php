<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\Application;
use App\Models\Service;
use App\Models\ServiceTransaction;
use App\Models\VisaType;

class AgentInvoiceService
{
    public function getRecords($agent, $fromDate,$toDate)
    {
        if(!$agent && !$fromDate && !$toDate){
            return [];
        }

        if ($agent && !is_null($agent) && $agent !='no_result') {
            $agentData = $this->getAgentData($agent, $fromDate, $toDate);
            $agentData['agent'] = Agent::query()->find($agent); // Add agent_id to the data
            $data['agents'][] = $agentData;
            return $data;
        } else{
            // Display data for all agents with applications this week or service transactions
            return  $this->getAllAgentsData($fromDate, $toDate);

        }

        return [];
    }

    public function getAgentData($agentId, $from, $to)
    {
        $data = ['visas' => [], 'services' => []];

        $visas = VisaType::query()->get();

        foreach ($visas as $visa) {
            $applications = Application::query()
                ->where('visa_type_id', $visa->id)
                ->where('travel_agent_id', $agentId);

            if ($from && $to) {
                $applications->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            }

            $applications = $applications->get(); // Assign the results to the variable

            $visa->qty = $applications->count();
            $data['visas'][] = $visa;
        }

        $services = Service::query()->get();

        foreach ($services as $service) {
            $serviceTransactions = ServiceTransaction::query()
                ->where('agent_id', $agentId)
                ->where('service_id', $service->id);

            if ($from && $to) {
                $serviceTransactions->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            }

            $serviceTransactions = $serviceTransactions->get(); // Assign the results to the variable

            $service->qty = $serviceTransactions->count();
            $data['services'][] = $service;
        }

        return $data;
    }

    protected function getAllAgentsData($from, $to)
    {
        $data = ['agents' => []];

        // Get all agents with applications this week
        if($from && $to){
            $agentsWithApplications = Application::whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->pluck('travel_agent_id')
                ->unique();

            // Get all agents with service transactions
            $agentsWithServiceTransactions = ServiceTransaction::whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->pluck('agent_id')
                ->unique();
        }else{

            $agentsWithApplications = Application::pluck('travel_agent_id')->unique();

            // Get all agents with service transactions
            $agentsWithServiceTransactions = ServiceTransaction::pluck('agent_id')->unique();
        }

        $allAgents = $agentsWithApplications->merge($agentsWithServiceTransactions)->unique();

        // Fetch data for each agent
        foreach ($allAgents as $agentId) {
            $agentData = $this->getAgentData($agentId, $from, $to);
            $agentData['agent'] = Agent::query()->find($agentId); // Add agent_id to the data
            $data['agents'][] = $agentData;
        }

        return $data;
    }

}
