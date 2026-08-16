
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password notification</title>
    <style>
            body{
                background-color: #f8f9fa;
                color:#8094ae;

            }
            a{
                color:#7367f0;
            }

            ul {
                display: inline-block;
                text-align: center;
                overflow: hidden;
                padding-left: 0px;
                margin-bottom: 0px!important;
            }
            ul li{
                float: left;
                list-style: none;
                padding:5px;
            }
            ul li a{
                cursor: pointer;
                display: block;
            }

    </style>
</head>
    <body style="margin: 10;">

        <table style="width:100%;height:50px;max-width:650px;margin: auto;">
                <tr>
                    <td style="text-align: center;padding:30px 10px">

                    </td>
                </tr>
        </table>

        <table style="width:100%;height:50px;max-width:650px;margin: auto;background-color: white;box-shadow: 0px 0px 5px rgba(84, 82, 82, 0.1);border-radius: .25rem;">
                <tr>
                    <td style="padding:30px;line-height: 1.5;
                    overflow: hidden;" colspan="2">
                        <div style="margin-left: -35px;
                        padding: 20px;
                        margin-right: -35px; 
                        padding-bottom: 10px;
                        margin-top: -35px;text-align: center;">
                            <a href="{{ url('/') }}"><img src="{{ $logo }}" style="height: 40px;"/></a>
                        </div>
                        <h1>Hello!</h1>
                        <p> You are receiving this email because we received a password reset request for your account.</p>
                        <div style="text-align: center">
                            @if ($otp && !empty($otp))
                                <p>Please use your password reset One Time Password (OTP):</p>
                                <h2>{{ @$otp }}</h2>
                            @else
                                <a href="{{ url('password/reset/'.$token.'?email='.$email)  }}" style="background-color: #7367f0;color: white; margin-left:10px;padding: 7px 15px; border: none;text-decoration: none;border-radius: 3px;">Reset Password</a>
                            @endif
                        </div>

                        <p>If you did not request a password reset, no further action is required.</p>
                        <p>Regards,<br/>{{ $signature }}</p>
                    </td>
                </tr>

        </table>
        <table style="width:100%;height:50px;max-width:650px;margin: auto; ">

                <tr>
                    <td style="padding:0px 30px;text-align: center;">
                        <p style="font-size: 13px;">
                            {!! $copyright !!}
                        </p>
                    </td>
                </tr>
        </table>
    </body>
</html>

