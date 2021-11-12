<style>
    .btn{
        display: inline-block;
        margin-bottom: 0;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        text-decoration: none;
    }

    .btn-primary{
        color: #fff;
        background-color: #337ab7;
        border-color: #2e6da4;
    }
</style>
@component('mail::message')
# Email Verification

Hi, {{$user_data['name']}}.
<h1>Your Login Credential is</h1>
<h5>Email : {{$user_data['name']}}</h5>
<h4>Password : {{decrypt($user_data['password'])}}</h4>
<a href="{{$url}}" class="btn btn-primary">
Verify Email
</a>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
