{{-- layout --}}
@extends('layouts.app')

{{-- page title --}}
@section('title', 'Donation Receipt')

{{-- vendor styles --}}
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/bootstrap4.css') }}" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

        body {
            font-family: Roboto;
        }

        .form-inline .form-group .gj-datepicker [role=right-icon] i.gj-icon,
        #add_room .gj-datepicker-bootstrap .btn i.gj-icon {
            top: 5px;
        }

        .print_field {
            display: inline-block;
            border-bottom: 1px solid #212529;
            text-align: left;
            font-weight: 600;
            margin-left: 5px;
            vertical-align: top;
            min-height: 20px;
        }

        .ruppes_box {
            border: 2px solid;
            border-radius: 35px;
            padding: 10px 15px;
            width: 145px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ruppes_box img {
            width: 15px;
        }

        .support {
            text-align: center;
            padding-top: 20px;
        }

        .payment_recieved {
            float: right;
        }

        .payment_recieved p+p {
            margin-top: 0;
        }

        .payment_recieved p.recevier_name {
            margin-top: 20px;
            font-weight: 800;
        }

        .payment_recieved p {
            margin-bottom: 0;
            line-height: 1;
            margin-top: 30px;
        }

        .ruppes_box .amount {
            margin-left: 10px;
            font-weight: bold;
            font-size: 15px;
        }

        .new_print+.new_print {
            margin-top: 20px;
            border-top: 1px dashed #ccc;
            padding-top: 20px;
        }

        a h1 {
            color: #636578;
        }

        a:hover {
            text-decoration: none;
        }

        @page {
            size: 100% 100%;
            padding: 0px !important;
            margin: 0px 30px !important;
        }

        .btn-primary {
            color: #fff;
            background-color: #DA5438;
            border-color: #DA5438;
        }

        @media print {
            body {
                /* zoom: 100%; */
                margin: 0px !important;
                padding: 0px;
                /* color: red; */
            }

            .card-body {
                border: 1px dashed #ccc;
            }

            footer, aside {
                display: none;
            }

            .card-body .new_print{
                /* margin-top: 170px !important; */
            }
        }
    </style>
@endsection

@section('content')
    {{-- <div class="card"> --}}
        <div class="card-header bg-white d-print-none">
            <h2>Income Receipt
                <div class="float-right ml-2">
                    <a class="btn btn-primary" href="{{ route('income.index') }}"><i class="fa fa-list"></i>
                        Income List</a>
                    <a class="btn btn-primary" style="padding: 0.575rem .75rem;" onclick="javascript:window.print()"><i class="fa fa-print text-white"></i></a>
                </div>
            </h2>
        </div>

        <div class="card-body border:none">
            <div class="new_print">
                <div class="row my-5">
                    <div class="col m12">
                        <div style="text-align: center;">
                            <h3 class="text-uppercase red_text" style="font-weight: bold;font-size: 23px;">શ્રી બળદીયા ગ્રામ્ય વિકાસ
                                સમિતિ
                            </h3>
                            <h4 class="text-uppercase red_text" style="font-weight: bold;font-size: 20px;"> ટ્રસ્ટ રજી. નં ઈ-૩૬૯-કચ્છ
                            </h4>
                            <h5 class="text-uppercase red_text" style="font-weight: bold;font-size: 17px;"> મુ. બળદીયા, તા. ભુજ-કચ્છ.
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 25px; font-size: 16px;">
                    <div class="col m6">
                        <div>
                            નંબર: <span class="print_field"
                                style="width: 200px; font-size: 19px;">{{ $data->id }}</span>
                        </div>
                    </div>
                    <div class="col m6">
                        <div style="float: right;">
                            તા.: <span class="print_field"
                                style="width: 200px; font-size: 17px;">{{ $data->paid_date ? \DateTime::createFromFormat('Y-m-d', $data->paid_date)->format('d/m/Y') : '' }}</span>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 22px; font-size: 16px;">
                    <div class="col m6 pr-0">
                        <div style="font-size: 16px;">
                            શ્રીમાન:<span class="print_field" style="width: calc(100% - 54px); font-size: 19px;">
                                {{ $data->member_id ? $data->member->full_name : '' }}

                            </span>
                        </div>
                    </div>
                    <div class="col m6">
                        <div style="font-size: 16px;">
                            ગામ: <span class="print_field" style="width: calc(100% - 41px); font-size: 17px;">
                                {{ $data->member->addresses->first()->gaam }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 22px; font-size: 16px;">
                    <div class="col m6 pr-0">
                        રૂ. <span class="print_field"
                            style="width: calc(100% - 27px); font-size: 17px;">{{ number_format($data->amount, 2) }}</span>
                    </div>
                    <div class="col m6">
                        અંકે રૂપિયા: <span class="print_field"
                            style="width: calc(100% - 115px); font-size: 17px;">{{ $amountInWords }}</span> પૂરા
                    </div>
                </div>
                <div class="row" style="margin-top: 22px; font-size: 16px;">
                    <div class="col m12">
                        બાબત: <span class="print_field" style="width: calc(100% - 111px);">{{ $data->note }}</span> મલ્યા છે
                        </span>
                    </div>
                </div>
                <div class="row" style="margin: 100px 0px; font-size: 16px;">
                    <div class="col m4" style="text-align: left">
                        <div class="ruppes_box">
                            <img src="http://guest.sklpsahmedabad.com/assets/rs.png">
                            <span class="amount"
                                style="font-size: 20px; vertical-align: middle;">{{ number_format($data->amount, 2) }}</span>
                        </div>
                    </div>
                    {{-- <div class="col m4" style="text-align: center">
                        <div class="support">
                            <strong>Thanks for Support</strong>
                        </div>
                    </div> --}}
                    <div class="col m4" style="text-align: center;">
                        <div class="payment_recieved">
                            {{-- <p class="recevier_name">
                                {{ auth()->user()->full_name }}
                            </p> --}}
                            <p>વસુલ કરનારની સહી</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="new_print">
                <div class="row my-5">
                    <div class="col m12">
                        <div style="text-align: center;">
                            <h3 class="text-uppercase red_text" style="font-weight: bold;font-size: 23px;">શ્રી બળદીયા ગ્રામ્ય વિકાસ
                                સમિતિ
                            </h3>
                            <h4 class="text-uppercase red_text" style="font-weight: bold;font-size: 20px;"> ટ્રસ્ટ રજી. નં ઈ-૩૬૯-કચ્છ
                            </h4>
                            <h5 class="text-uppercase red_text" style="font-weight: bold;font-size: 17px;"> મુ. બળદીયા, તા. ભુજ-કચ્છ.
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 25px; font-size: 16px;">
                    <div class="col m6">
                        <div>
                            નંબર: <span class="print_field"
                                style="width: 200px; font-size: 19px;">{{ $data->id }}</span>
                        </div>
                    </div>
                    <div class="col m6">
                        <div style="float: right;">
                            તા.: <span class="print_field"
                                style="width: 200px; font-size: 17px;">{{ $data->paid_date ? \DateTime::createFromFormat('Y-m-d', $data->paid_date)->format('d/m/Y') : '' }}</span>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 22px; font-size: 16px;">
                    <div class="col m6 pr-0">
                        <div style="font-size: 16px;">
                            શ્રીમાન:<span class="print_field" style="width: calc(100% - 54px); font-size: 19px;">
                                {{ $data->member_id ? $data->member->full_name : '' }}

                            </span>
                        </div>
                    </div>
                    <div class="col m6">
                        <div style="font-size: 16px;">
                            ગામ: <span class="print_field" style="width: calc(100% - 41px); font-size: 17px;">
                                {{ $data->member->addresses->first()->gaam }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 22px; font-size: 16px;">
                    <div class="col m6 pr-0">
                        રૂ. <span class="print_field"
                            style="width: calc(100% - 27px); font-size: 17px;">{{ number_format($data->amount, 2) }}</span>
                    </div>
                    <div class="col m6">
                        અંકે રૂપિયા: <span class="print_field"
                            style="width: calc(100% - 115px); font-size: 17px;">{{ $amountInWords }}</span> પૂરા
                    </div>
                </div>
                <div class="row" style="margin-top: 22px; font-size: 16px;">
                    <div class="col m12">
                        બાબત: <span class="print_field" style="width: calc(100% - 111px);">{{ $data->note }}</span> મલ્યા છે
                        </span>
                    </div>
                </div>
                <div class="row" style="margin: 100px 0px; font-size: 16px;">
                    <div class="col m4" style="text-align: left">
                        <div class="ruppes_box">
                            <img src="http://guest.sklpsahmedabad.com/assets/rs.png">
                            <span class="amount"
                                style="font-size: 20px; vertical-align: middle;">{{ number_format($data->amount, 2) }}</span>
                        </div>
                    </div>
                    {{-- <div class="col m4" style="text-align: center">
                        <div class="support">
                            <strong>Thanks for Support</strong>
                        </div>
                    </div> --}}
                    <div class="col m4" style="text-align: center;">
                        <div class="payment_recieved">
                            {{-- <p class="recevier_name">
                                {{ auth()->user()->full_name }}
                            </p> --}}
                            <p>વસુલ કરનારની સહી</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- </div> --}}
@endsection