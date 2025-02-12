<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vidyagxp - Software</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .w-10 {
        width: 10%;
    }

    .w-20 {
        width: 20%;
    }

    .w-25 {
        width: 25%;
    }

    .w-30 {
        width: 30%;
    }

    .w-40 {
        width: 40%;
    }

    .w-50 {
        width: 50%;
    }

    .w-60 {
        width: 60%;
    }

    .w-70 {
        width: 70%;
    }

    .w-80 {
        width: 80%;
    }

    .w-90 {
        width: 90%;
    }

    .w-100 {
        width: 100%;
    }

    .h-100 {
        height: 100%;
    }

    header table,
    header th,
    header td,
    footer table,
    footer th,
    footer td,
    .border-table table,
    .border-table th,
    .border-table td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    table {
        width: 100%;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    footer .head,
    header .head {
        text-align: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    @page {
        size: A4;
        margin-top: 160px;
        margin-bottom: 60px;
    }

    header {
        position: fixed;
        top: -140px;
        left: 0;
        width: 100%;
        display: block;
    }

    footer {
        width: 100%;
        position: fixed;
        display: block;
        bottom: -40px;
        left: 0;
        font-size: 0.9rem;
    }

    footer td {
        text-align: center;
    }

    .inner-block {
        padding: 10px;
    }

    .inner-block tr {
        font-size: 0.8rem;
    }

    .inner-block .block {
        margin-bottom: 30px;
    }

    .inner-block .block-head {
        font-weight: bold;
        font-size: 1.1rem;
        padding-bottom: 5px;
        border-bottom: 2px solid #4274da;
        margin-bottom: 10px;
        color: #4274da;
    }

    .inner-block th,
    .inner-block td {
        vertical-align: baseline;
    }

    .table_bg {
        background: #4274da57;
    }
</style>

<body>

    <header>
        <table>
            <tr>
                <td class="w-70 head">
                    Department Wise Employees Job Role
                </td>
                <td class="w-30">
                    <div class="logo">
                        <img src="https://navin.mydemosoftware.com/public/admin/assets/images/connexo.png" alt=""
                            class="w-100">
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Employee ID.</strong>
                </td>
                <td class="w-30">
                    {{ $data->full_employee_id }}
                </td>
                <td class="w-30">
                    <strong>Employee Name.</strong>
                </td>
                <td class="w-30">
                    {{ $data->employee_name }}
                </td>

            </tr>
        </table>
    </header>

    <footer>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Printed On :</strong> {{ date('d-M-Y') }}
                </td>
                <td class="w-40">
                    <strong>Printed By :</strong> {{ Auth::user()->name }}
                </td>

            </tr>
        </table>
    </footer>
    <div class="inner-block">
        <div class="content-table">
            <div class="block">
                <div class="block-head">
                    General Information
                </div>

                <table>
                    <tr>
                        <th class="w-20">Site Division/Project</th>

                        <td class="w-80">
                            @if ($data->location)
                                {{ $data->location }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class ="w-20">Year</th>
                        <td class="w-30">
                            @if ($data->year)
                                {{ $data->year }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    @php
                    // Define a mapping of short codes to full names
                    $prefixMap = [
                    'PW' => 'Permanent Workers',
                    'PS' => 'Permanent Staff',
                    'OS' => 'Others Separately',
                    ];

                    // Get the full names using the prefix map
                    // $lastPrefixFullName = $prefixMap[$lastDocument->prefix] ?? 'N/A';
                    $currentPrefixFullName = $prefixMap[$data->prefix] ?? 'N/A';
                    @endphp
                    <tr>
                    <th class ="w-20">Reviewer</th>
                        <td class="w-30">
                            @if ($data->reviewer)
                                {{ $data->reviewer }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class ="w-20">Approvar</th>
                        <td class="w-30">
                            @if ($data->approvar)
                                {{ $data->approvar }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class ="w-20">Document Number</th>
                        <td cla
                        ss="w-30">
                            @if ($data->document_number)
                                {{ $data->document_number }}
                                
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class ="w-20">Department
                        </th>
                        <td class="w-30">
                            @if ( Helpers::getFullDepartmentName($data->department) )
                                {{  Helpers::getFullDepartmentName($data->department) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>

                        <th class ="w-20">Employee Name</th>
                        <td class="w-30">
                            @if (\App\Models\Employee::find($data->employee_name)?->employee_name ?? 'NA')
                                {{ \App\Models\Employee::find($data->employee_name)?->employee_name ?? 'NA' }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class ="w-20">Job Role</th>
                        <td class="w-30">
                            @if ($data->job_role)
                                {{ $data->job_role }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <!-- <th class ="w-20">Employee Code</th>
                        <td class="w-30">
                            @if ($data->employee_code)
                                {{ $data->employee_code }}
                            @else
                                Not Applicable
                            @endif
                        </td> -->

                        <th class="w-20">Prepared By</th>
                        <td class="w-80">
                            @if ($data->Prepared_by)
                                {{$data->Prepared_by}}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Prepared On</th>
                        <td class="w-30">
                            @if ($data->prefix)
                                {{ $prefixMap[$data->Prepared_date] ?? 'Not Applicable' }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                    </tr>
                    
                </table>

            </div>
        </div>
    </div>



                </table>

            </div>
        </div>
    </div>


                </table>
            </div>
        </div>
    </div>


    <footer>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Printed On :</strong> {{ date('d-M-Y') }}
                </td>
                <td class="w-40">
                    <strong>Printed By :</strong> {{ Auth::user()->name }}
                </td>

            </tr>
        </table>
    </footer>

</body>

</html>
