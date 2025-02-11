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

            $applications = $applications->get();

            $totalAmount = 0; // Initialize total amount variable

            foreach ($applications as $application) {
                // Assuming these fields exist, adjust them based on your actual fields
                $serviceFee = $application->service_fee ?? 0;
                $dubaiFee = $application->dubai_fee ?? 0;
                $vat = $application->vat ?? 0;

                // Calculate the total amount for each application
                $totalAmount += $serviceFee + $dubaiFee + $vat;
            }

            $visa->qty = $applications->count();
            $visa->totalAmount = $totalAmount; // Assign total amount to the visa
            if($totalAmount > 0){
                $data['visas'][] = $visa;    
            }
            
        }

        $services = Service::query()->get();

        foreach ($services as $service) {
            $serviceTransactions = ServiceTransaction::query()
                ->where('status', '!=', 'deleted')
                ->where('agent_id', $agentId)
                ->where('service_id', $service->id);

            if ($from && $to) {
                $serviceTransactions->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            }

            $serviceTransactions = $serviceTransactions->get();

            $totalAmount = 0; // Initialize total amount variable

            foreach ($serviceTransactions as $transaction) {
                // Assuming these fields exist, adjust them based on your actual fields
                $amount = $transaction->amount ?? 0;

                // Calculate the total amount for each service transaction
                $totalAmount += $amount;
            }

            $service->qty = $serviceTransactions->count();
            $service->totalAmount = $totalAmount; // Assign total amount to the service
            if($totalAmount > 0){
                $data['services'][] = $service;    
            }
            
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
                ->where('status', '!=', 'deleted')

                ->whereDate('created_at', '<=', $to)
                ->pluck('agent_id')
                ->unique();
        }else{

            $agentsWithApplications = Application::pluck('travel_agent_id')->unique();

            // Get all agents with service transactions
            $agentsWithServiceTransactions = ServiceTransaction::pluck('agent_id')->where('status', '!=', 'deleted')
                ->unique();
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
