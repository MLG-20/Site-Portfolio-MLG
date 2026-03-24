<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message Portfolio</title>
</head>
<body style="margin:0;padding:0;background-color:#0f1117;font-family:'Segoe UI',Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0f1117;padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

                    <!-- HEADER -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#00c6ff,#0072ff);border-radius:12px 12px 0 0;padding:40px 48px;text-align:center;">
                            <p style="margin:0 0 8px 0;font-size:13px;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:rgba(255,255,255,0.75);">Portfolio MLG</p>
                            <h1 style="margin:0;font-size:26px;font-weight:700;color:#ffffff;line-height:1.3;">Nouveau message reçu</h1>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="background-color:#1a1f2e;padding:40px 48px;">

                            <p style="margin:0 0 32px 0;font-size:15px;color:#a0aec0;line-height:1.7;">
                                Quelqu'un a rempli le formulaire de contact de ton portfolio. Voici les détails :
                            </p>

                            <!-- Info cards -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding-bottom:16px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#242938;border-radius:8px;border-left:3px solid #00c6ff;">
                                            <tr>
                                                <td style="padding:14px 20px;">
                                                    <p style="margin:0 0 3px 0;font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:#00c6ff;">Nom</p>
                                                    <p style="margin:0;font-size:16px;font-weight:600;color:#ffffff;">{{ $contact->name }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom:16px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#242938;border-radius:8px;border-left:3px solid #00c6ff;">
                                            <tr>
                                                <td style="padding:14px 20px;">
                                                    <p style="margin:0 0 3px 0;font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:#00c6ff;">Email</p>
                                                    <p style="margin:0;font-size:16px;color:#ffffff;">
                                                        <a href="mailto:{{ $contact->email }}" style="color:#63b3ed;text-decoration:none;">{{ $contact->email }}</a>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @if($contact->phone)
                                <tr>
                                    <td style="padding-bottom:16px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#242938;border-radius:8px;border-left:3px solid #00c6ff;">
                                            <tr>
                                                <td style="padding:14px 20px;">
                                                    <p style="margin:0 0 3px 0;font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:#00c6ff;">Téléphone</p>
                                                    <p style="margin:0;font-size:16px;color:#ffffff;">{{ $contact->phone }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding-bottom:24px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#242938;border-radius:8px;border-left:3px solid #00c6ff;">
                                            <tr>
                                                <td style="padding:14px 20px;">
                                                    <p style="margin:0 0 3px 0;font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:#00c6ff;">Sujet</p>
                                                    <p style="margin:0;font-size:16px;font-weight:600;color:#ffffff;">{{ $contact->subject }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Message -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#242938;border-radius:8px;margin-bottom:32px;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <p style="margin:0 0 10px 0;font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:#00c6ff;">Message</p>
                                        <p style="margin:0;font-size:15px;color:#e2e8f0;line-height:1.8;white-space:pre-line;">{{ $contact->message }}</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}"
                                           style="display:inline-block;padding:14px 36px;background:linear-gradient(135deg,#00c6ff,#0072ff);color:#ffffff;font-size:15px;font-weight:600;text-decoration:none;border-radius:50px;letter-spacing:0.5px;">
                                            Répondre à {{ $contact->name }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color:#131720;border-radius:0 0 12px 12px;padding:24px 48px;text-align:center;border-top:1px solid #242938;">
                            <p style="margin:0 0 6px 0;font-size:13px;color:#4a5568;">
                                Reçu le {{ $contact->created_at->format('d/m/Y à H:i') }}
                            </p>
                            <p style="margin:0;font-size:12px;color:#2d3748;">
                                Portfolio MLG — Mamadou Lamine GUEYE
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
