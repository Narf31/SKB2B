@extends('emails.templates.default')

@section('title')
    Доступ в личный кабинет
@stop

@section('content')
    <div style="padding: 0 15px;">
    <table width="100%" cellspacing="0" cellpadding="0"
           role="presentation"
           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
        <tr style="border-collapse:collapse;">
            <td align="left" style="padding:0;Margin:0;">
                <h1 style="Margin:0;line-height:36px;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;font-size:30px;font-style:normal;font-weight:normal;color:#4A7EB0;">Доступ в личный кабинет</h1>
            </td>
        </tr>
        <tr style="border-collapse:collapse;">
            <td style="padding:0;Margin:0;padding-top:5px;padding-bottom:20px;font-size:0;"
                align="left">
                <table width="5%" height="100%" cellspacing="0"
                       cellpadding="0" border="0" role="presentation"
                       style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                    <tr style="border-collapse:collapse;">
                        <td style="padding:0;Margin:0px;border-bottom:2px solid #999999;background:rgba(0, 0, 0, 0) none repeat scroll 0% 0%;height:1px;width:100%;margin:0px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="border-collapse:collapse;">
            <td align="left"
                style="padding:0;Margin:0;padding-bottom:10px;"><p
                        style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#666666;">
                    <span style="font-size:16px;line-height:24px;">Здравствуйте, {{$client_title}}</span>
                </p></td>
        </tr>

        <tr style="border-collapse:collapse;">
            <td align="left"
                style="padding:0;Margin:0;padding-top:20px;padding-bottom:20px;">
                    <span class="es-button-border"
                          style="border-style:solid;border-color:#4A7EB0;background:#2CB543;border-width:0px;display:inline-block;border-radius:0px;width:auto;">
                        <span class="es-button"
                                style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:18px;color:#4A7EB0;border-style:solid;border-color:#EFEFEF;border-width:10px 25px;display:inline-block;background:#EFEFEF;border-radius:0px;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center;">Логин: {{$email}}</span>


                    </span>
                <span class="es-button-border"
                      style="border-style:solid;border-color:#4A7EB0;background:#2CB543;border-width:0px;display:inline-block;border-radius:0px;width:auto;">
                        <span class="es-button"
                              style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:18px;color:#4A7EB0;border-style:solid;border-color:#EFEFEF;border-width:10px 25px;display:inline-block;background:#EFEFEF;border-radius:0px;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center;">Пароль: {{$pass}}</span>

                    </span>
            </td>
        </tr>
        <tr style="border-collapse:collapse;">
            <td align="left" style="padding:0;Margin:0;">
                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#666666;">
                    Сайт <a target="_blank" href="{{urlClient('/')}}">{{urlClient('/')}}</a>
                </p>
            </td>
        </tr>
    </table>
    </div>
@stop