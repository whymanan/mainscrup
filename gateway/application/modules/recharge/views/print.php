<html>
<head>
    <style>
        .box {
            height: 700px;
            border: 5px solid #162784;
            width: 600px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            overflow: hidden;
        }
        .box::before
        {
            /*background-image: url(https://atmoonpe.com/gateway/optimum/logoside.png);*/
    background-position: 0 0;
    background-repeat: no-repeat;
    position: sticky;
    background-position-y: center;
    transform: rotate(345deg);
    content: "";
    position: absolute;
    width: 200%;
    height: 200%;
    left: 0%;
    top: -65%;
    z-index: -1;
    background-size: 50%;
    opacity: 0.2;
        }

        table {
            line-height: 30px;
            padding-left: 10%;
        }

        .social {
            padding: 10px;
            border-radius: 50%;
            background: #162784;
            color: white;
            margin-left: 15px;
        }
        .image>img
        {
            height: 81px;
           padding-top: 13px;
           padding-bottom: 50px;
        }
        .image
        {
            text-align:center;
        }
        .box
        {
    /*        background-image: url(https://atmoonpe.com/gateway/optimum/logoside.png);*/
    /*      background-position: center;*/
    /*     background-repeat: no-repeat;*/
    /* background-attachment: fixed; */
    /*     background-size: contain;*/
        }
        /*.box:before*/
        /*{*/
        /*    content: "" !important;*/
        /*  position: absolute !important;*/
        /* width: 200% !important;*/
        /*height: 200% !important;*/
        /*top: -50% !important;*/
        /* left: -50% !important;*/
        /*z-index: -1;*/
        /*  background: url(https://atmoonpe.com/gateway/optimum/logoside.png) 0 0 repeat !important;*/
        /* transform: rotate(30deg) !important;*/
        /*}*/
        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            @page {
                margin-top: 0;
                margin-bottom: 0;
            }

            body {
                padding-top: 72px;
                padding-bottom: 72px;
            }
           * {
         -webkit-print-color-adjust: exact !important;   /* Chrome, Safari, Edge */
         color-adjust: exact !important;                 /*Firefox*/ 
         }
        }
    </style>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>
<body onload="window.print()">
    <a class="no-print" href="<?php echo base_url("recharge"); ?>" class="btn btn-primary">Back</a>
    <div>
        <div class="container box" >
            <div class="image">
                <img src="<?php echo base_url() ?>optimum/logoside.png">
                <h2 style="padding-left: 16px;margin-top: -11px;">Transaction Details</h2>
            </div>
            <table>
                <tr>
                    <td width="150px">Transaction Status</td>
                    <td>:</td>
                    <td><?php echo $result->transection_msg ?></td>
                </tr>
                <tr>
                    <td>Amount</td>
                    <td>:</td>
                    <td>Rs <?php echo $result->transection_amount ?></td>
                </tr>
                <tr>
                    <td>Transaction ID</td>
                    <td>:</td>
                    <td><?php echo $result->transection_id ?></td>
                </tr>
                 <tr>
                    <td>Member ID</td>
                    <td>:</td>
                    <td><?php echo $result->member_id ?></td>
                </tr>
                <?php if(isset($result->operator) && !empty($result->operator)){?>
                <tr>
                    <td>Operator Name</td>
                    <td>:</td>
                    <td><?php echo $result->operator ?></td>
                </tr>
                <?php }?>
                <tr>
                    <td>Date & Time</td>
                    <td>:</td>
                    <td><?php echo $result->created ?></td>
                </tr>
                <tr>
                    <td>Service Name</td>
                    <td>:</td>
                    <td style="text-transform: capitalize;"><?php echo $result->transection_type ?></td>
                </tr>
                <tr>
                    <?php if($result->api_requist=='prepaid' || $result->api_requist=='postpaid'){?>
                    <td>Mobile Number</td>
                    <?php }else{?>
                    <td>Transation Number</td>
                    <?php }?>
                    <td>:</td>
                    <td><?php echo $result->transection_mobile ?></td>
                </tr>
            </table>
            <hr width="530px">
            </br>
           
            <div style="float: right;margin-right: 60px;">
                <span class="social"><i class="fab fa-twitter"></i></span>
                <span class="social"><i class="fab fa-instagram"></i></span>
            </div>
        </div>
    </div>

</body>

</html>