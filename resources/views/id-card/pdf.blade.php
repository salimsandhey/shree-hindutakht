<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px;
            background: white;
            width: 400px;
            height: 250px;
        }
        .id-card { 
            width: 350px; 
            height: 200px;
            border: 3px solid #b93a20; 
            padding: 20px;
            position: relative;
        }
        .header { 
            text-align: center; 
            background: #b93a20; 
            color: white; 
            padding: 10px; 
            margin: -20px -20px 20px -20px;
            font-size: 14px;
            font-weight: bold;
        }
        .content { 
            display: flex; 
            justify-content: space-between;
        }
        .details { 
            flex: 1; 
            font-size: 11px;
            line-height: 1.5;
        }
        .qr { 
            text-align: center;
            margin-left: 20px;
        }
        .field { 
            margin: 8px 0; 
        }
        .label { 
            font-weight: bold; 
        }
        .footer {
            text-align: right;
            font-size: 8px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="id-card">
        <div class="header">
            <div>SHREE HINDUTAKHT</div>
            <div style="font-size: 10px; margin-top: 2px;">MEMBER ID CARD</div>
        </div>
        <div class="content">
            <div class="details">
                <div class="field"><span class="label">ID:</span> {{$member->member_id}}</div>
                <div class="field"><span class="label">Name:</span> {{$member->name}}</div>
                <div class="field"><span class="label">Email:</span> {{$member->email ?? 'N/A'}}</div>
                <div class="field"><span class="label">Joined:</span> {{$member->created_at->format('M d, Y')}}</div>
                <div class="field"><span class="label">Status:</span> {{ucfirst($member->status)}}</div>
                <div class="field"><span class="label">Valid Until:</span> {{now()->addYear()->format('M d, Y')}}</div>
            </div>
            <div class="qr">
                <img src="data:image/png;base64,{{$qr_code}}" width="80" height="80">
                <div style="font-size: 8px; margin-top: 5px;">Scan to verify</div>
            </div>
        </div>
        <div class="footer">
            www.shreehindutakht.com
        </div>
    </div>
</body>
</html>