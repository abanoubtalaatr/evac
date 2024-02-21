 <div class="login-head">
    <select
        x-data="{current_lang:'{{app()->getLocale()}}',other_lang:'{{app()->getLocale()=='en'? 'ar' : 'en'}}'}"
        x-on:change="window.location.href=window.location.href.replace('/'+current_lang+'/','/'+other_lang+'/');"
        class="form-control select-login">
        <option @if(app()->getLocale()=='en') selected @endif value='en'>English</option>
        <option @if(app()->getLocale()=='ar') selected @endif value='ar'>اللغة العربية</option>
    </select>
</div>
