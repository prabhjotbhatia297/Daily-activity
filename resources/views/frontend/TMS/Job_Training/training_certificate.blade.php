<!-- Print Button -->
<div>
    <div class="pm-certificate-container">
        <div class="outer-border"></div>
        <div class="inner-border"></div>

        <div class="pm-certificate-border">
            <!-- Logos Section -->
            <div class="pm-certificate-logos text-center">
                <img src="{{ asset('user/images/connexo.png') }}" alt="connexo Logo" class="logo logo-left">
                {{-- <img src="{{ asset('user/images/symbiotec_pharmalab_pvt_ltd__logo.jpg') }}" alt="Vidhya GxP Logo" class="logo logo-left"> --}}
            </div>

            <div class="pm-certificate-header">
                <div class="pm-certificate-title text-center">
                    <h2>TRAINING COMPLETION CERTIFICATE</h2>
                </div>
            </div>

            <div class="pm-certificate-body">
                <div class="pm-certificate-block">
                    <p class="text-center">
                        This is to certify that Mr. / Ms.____________________
                        {{-- <strong>{{ \App\Models\Employee::find($induction->name_employee)?->employee_name ?? 'Employee not found' }}</strong> --}}
                        working in _______________department has successfully completed all the SOP trainings assigned to him/her as per the training need identification(TNI) on
                        dated_______________and Employee is allowed to work in the area.
                    </p>
                </div>

                <div class="pm-certificate-footer">
                    <div class="pm-certified text-center">
                        <span class="bold block" style="display: block; margin-bottom: 5px;font-size: 14px;">Employee name:</span>
                        {{-- <strong>{{ $induction->evaluation_complete_by }} /
                            {{ \Carbon\Carbon::parse($induction->evaluation_complete_on)->format('d-M-Y') }}
                            </strong> --}}
                        {{-- <span class="pm-empty-space block underline"></span> --}}
                        <span class="bold block" style="display: block; margin-bottom: 5px;font-size: 14px;">Employee code:</span>
                        {{-- <span class="bold block" style="display: block; margin-bottom: 5px;">Employee Code:</span> --}}
                        <span class="bold block" style="display: block; margin-bottom: 35px;font-size: 14px;">Sign & Date:</span>
                        {{-- <span class="pm-empty-space block underline"></span> --}}
                        <span class="bold block" style="display: block; margin-bottom: 5px;font-size: 14px;">Name:</span>
                        <span class="bold block" style="display: block; margin-bottom: 5px;font-size: 14px;">Sign & Date:</span>
                        <span class="bold block" style="font-size: 15px;">Checked by</span>
                        <span class="bold block" style="display: block; margin-bottom: 35px;font-size: 14px;">Head / Designee of the department</span>

                        <span class="bold block" style="display: block; margin-bottom: 5px;font-size: 14px;">Name:</span>
                        <span class="bold block" style="display: block; margin-bottom: 5px;font-size: 14px;">Sign & Date:</span>
                        <span class="bold block" style="font-size: 14px;">Approved by</span>
                        <span class="bold block" style="font-size: 14px;">Head QA/designee</span>

                    </div>
                    {{-- <div class="pm-certified text-center">
                            <span class="bold block">Sign / Date:</span> --}}
                    {{-- <strong>{{ $induction->approval_complete_by }} /
                                {{ \Carbon\Carbon::parse($induction->approval_complete_on)->format('d-M-Y') }}</strong> --}}
                    {{-- <span class="pm-empty-space block underline"></span>
                            <span class="bold block">Head QA/CQA</span>
                        </div> --}}
                </div>
            </div>
        </div>
    </div>

</div>
<div class="print-button-container text-center">
    <button onclick="downloadCertificate()" class="print-button">Download Certificate</button>
</div>

<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans|Pinyon+Script|Rochester');

    body {
        padding: 20px 0;
        background: #ccc;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin-left: 170px;
    }

    .cursive {
        font-family: 'Pinyon Script', cursive;
    }

    .sans {
        font-family: 'Open Sans', sans-serif;
    }

    .bold {
        font-weight: bold;
    }

    .block {
        display: block;
    }

    .underline {
        border-bottom: 1px solid #777;
        padding: 5px;
        margin-bottom: 15px;
    }

    .text-center {
        text-align: center;
    }

    .pm-empty-space {
        /* height: 40px; */
        width: 100%;
    }

    .pm-certificate-container {
        position: relative;
        width: 90%;
        max-width: 800px;
        background-color: #618597;
        padding: 30px;
        color: #333;
        font-family: 'Open Sans', sans-serif;
        box-shadow: 0 9px 15px rgb(18 5 23 / 60%);
    }

    .outer-border {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border: 2px solid #fff;
        pointer-events: none;
    }

    .inner-border {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        bottom: 10px;
        border: 2px solid #fff;
        pointer-events: none;
    }

    .pm-certificate-border {
        position: relative;
        padding: 20px;
        border: 1px solid #E1E5F0;
        background-color: rgba(255, 255, 255, 1);
    }


    .pm-certificate-logos {
        display: flex;
        justify-content: space-between;
        align-items: center;

    }

    .logo {
        max-width: 100px;
    }

    .logo-left {
        transform: scale(0.7);
        margin-bottom: 14px;
    }

    .logo-right {
        transform: scale(1.8);
        margin-right: 65px;
    }

    .pm-certificate-header {
        margin-bottom: 10px;
    }

    .pm-certificate-title h2 {
        font-size: 34px;
    }

    .pm-certificate-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        /* align-items: center; */
        text-align: left;
        line-height: 1.8;
    }

    .pm-certificate-block p,
    .pm-certificate-footer .pm-certified {
        text-align: left;
        line-height: 1.8; /* Adds space between lines */
        margin-bottom: 20px; /* Adds spacing between paragraphs */
    }

    .pm-certificate-block {
        text-align: center;
        /* border: 2px solid black; Full border around the entire block */
        /* padding: 20px; Padding inside the border */

    }

    .pm-name-text {
        font-size: 20px;
    }

    .pm-earned {
        margin: 15px 0 20px;
    }

    .pm-earned-text {
        font-size: 20px;
    }

    .pm-credits-text {
        font-size: 15px;
    }

    .pm-course-title {
        margin-bottom: 15px;
    }

    .pm-certified {
        font-size: 12px;
        width: 300px;
        margin-top: 0;
        text-align: center;
    }

    .pm-certificate-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin-top: 20px;
        flex-wrap: nowrap
    }

    @media print {
        .print-button {
            display: none;
        }

        .print-button-container {
            display: none;
        }
    }


    .print-button {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        margin-block-end: 700px;
    }


    @media print {
        body {
            background: none;
            -webkit-print-color-adjust: exact;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .pm-certificate-container {
            page-break-inside: avoid;
            page-break-after: avoid;
            width: 100%;
            height: auto;
            max-height: 100vh;
            overflow: hidden;
            box-shadow: none;
            background-color: #618597;
            padding: 30px;
            margin: 0 auto;
        }

        .outer-border,
        .inner-border {
            border-color: #d3d0d0;
        }

        .print-button,
        .print-button-container {
            display: none;

        }


        html,
        body {
            height: auto;
            max-height: 100vh;
            overflow: hidden;
        }
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>


<script>
    function downloadCertificate() {
        const element = document.querySelector('.pm-certificate-container');
        const options = {
            margin: [50, 50, 50, 50],

            filename: 'Training-completion-certificate.pdf',
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                orientation: 'landscape',
                unit: 'pt', // Points as unit for better accuracy
                format: [element.offsetWidth, element.offsetHeight] // Ensure content fits in one page

            }
        };
        html2pdf().from(element).set(options).save();
    }
</script>
