<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VidyaGxP - Software</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/fontawesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
   


    <style>
        * {
            font-family: 'Noto Sans', serif;
            box-sizing: border-box;
        }

        body {
            background-image: linear-gradient(120deg, #bfd0f2 0%, #bfd0f2 100%);
            margin: 0;
            padding: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #rcms_login_block {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
        }

        #rcms_login_block .login-form-block {
            width: 500px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            background-size: cover;
            background-position: center;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-bottom: 20px;
        }

        .logo-img {
            max-width: 210px;
            max-height: 101px;
            transition: transform 0.3s ease-in-out;
        }

        /* .logo-img:hover {
            transform: scale(1.1);
        } */

        .head {
            font-size: 2rem;
            font-weight: bold;
            /* text-transform: uppercase; */
            text-align: center;
            color: #bfd0f2;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        .group-input {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            border: 2px solid #bfd0f2;
            padding: 10px;
            border-radius: 8px;
        }

        .group-input label {
            font-size: 1.4rem;
            color: #bfd0f2;
            font-weight: bold;
            margin-right: 10px;
        }

        .group-input input,
        .group-input select {
            border: none;
            outline: none;
            flex: 1;
            /* padding: 10px; */
            background: transparent;
            color: #23201b;
            font-size: 1rem;
        }

        /* .group-input select {
            background: rgba(255, 255, 255, 0.5);
        }

        .group-input input::placeholder {
            color: #f8b195;
        } */

        #rcms_login_block input[type="submit"] {
            display: block;
            text-align: center;
            width: 100%;
            padding: 12px;
            background: linear-gradient(180deg, #427CE6 0%, #427CE6 100%);
            color: white;
            text-transform: uppercase;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            transition: all 0.3s linear;
            cursor: pointer;
            box-shadow: 0 4px 10px  #427CE6;
        }

        #rcms_login_block input[type="submit"]:hover {
            background: linear-gradient(180deg, #427CE6 0%, rgb(110, 143, 242) 100%);
            box-shadow: 0 6px 15px rgba(110, 172, 242, 0.6);
        }

        #preloader {
            backdrop-filter: blur(20px);
            z-index: 20;
            width: 100%;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #preloader .loader {
            width: 150px;
            height: 150px;
            background-image: linear-gradient(120deg, #427CE6 0%, #427CE6 100%);
            border-radius: 50%;
            position: relative;
            box-shadow: 0 0 30px 4px rgba(0, 0, 0, 0.5) inset,
                0 5px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        #preloader .loader:before,
        #preloader .loader:after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 45%;
            top: -40%;
            background-color: #fff;
            animation: wave 5s linear infinite;
        }

        #preloader .loader:before {
            border-radius: 30%;
            background: rgba(255, 255, 255, 0.4);
            animation: wave 5s linear infinite;
        }

        @keyframes wave {
            0% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

  

</head>

<body>

    {{-- ======================================
                    PRELOADER
    ======================================= --}}
    <div id="preloader">
        <span class="loader"></span>
    </div>

    <!-- Login Form Block -->
    <div id="rcms_login_block">
        <div class="login-form-block">
            <!-- Logo Container -->
            <div class="logo-container">
                <!-- First Logo -->
                {{-- <img src="https://www.medicefpharma.com/wp-content/uploads/2020/06/medicef-logo-new1.png" 
                alt="Medicef Pharma Logo" 
                class="logo-img" 
                style="width: 160px; height: auto; pointer-events: none;"> --}}
                <img src="https://ipc.gov.in/images/new_logo.png" alt="Logo 1" class="logo-img" style="scale: 0.8;">
           
                <!-- Second Logo -->
                <img src="{{ asset('user/images/vidhyagxp.png') }}" alt="Logo 2" class="logo-img">
            </div>
            <!-- Welcome Text -->
            <div class="head animated-header">
                Welcome To VidyaGxP
            </div>
            <!-- Login Form -->
            <form action="{{ url('rcms_check') }}" method="POST">
                @csrf
                <div class="group-input">
                    <label for="username"><i class="fa-solid fa-user" style="color: #265bc4"></i></label>
                    <input type="text" name="Username" placeholder="Enter Your User Name" >
                </div>
                <div class="group-input">
                    <label for="password"><i class="fa-solid fa-lock" style="color: #265bc4"></i></label>
                    <input type="password" name="password" placeholder="Enter Your Password">
                </div>
                <div class="group-input">
                    <label for="timezone"><i class="fa-solid fa-calendar-check" style="color: #265bc4"></i></label>
                    <select name="timezone">
                                <option value="Pacific/Midway">
                                    (GMT-11:00) Midway Island
                                </option>
                                <option value="US/Samoa">
                                    (GMT-11:00) Samoa
                                </option>
                                <option value="US/Hawaii">
                                    (GMT-10:00) Hawaii
                                </option>
                                <option value="US/Alaska">
                                    (GMT-09:00) Alaska
                                </option>
                                <option value="US/Pacific">
                                    (GMT-08:00) Pacific Time (US &amp;amp; Canada)
                                </option>
                                <option value="America/Tijuana">
                                    (GMT-08:00) Tijuana
                                </option>
                                <option value="US/Arizona">
                                    (GMT-07:00) Arizona
                                </option>
                                <option value="US/Mountain">
                                    (GMT-07:00) Mountain Time (US &amp;amp; Canada)
                                </option>
                                <option value="America/Chihuahua">
                                    (GMT-07:00) Chihuahua
                                </option>
                                <option value="America/Mazatlan">
                                    (GMT-07:00) Mazatlan
                                </option>
                                <option value="America/Mexico_City">
                                    (GMT-06:00) Mexico City
                                </option>
                                <option value="America/Monterrey">
                                    (GMT-06:00) Monterrey
                                </option>
                                <option value="Canada/Saskatchewan">
                                    (GMT-06:00) Saskatchewan
                                </option>
                                <option value="US/Central">
                                    (GMT-06:00) Central Time (US &amp;amp; Canada)
                                </option>
                                <option value="US/Eastern">
                                    (GMT-05:00) Eastern Time (US &amp;amp; Canada)
                                </option>
                                <option value="US/East-Indiana">
                                    (GMT-05:00) Indiana (East)
                                </option>
                                <option value="America/Bogota">
                                    (GMT-05:00) Bogota
                                </option>
                                <option value="America/Lima">
                                    (GMT-05:00) Lima
                                </option>
                                <option value="America/Caracas">
                                    (GMT-04:30) Caracas
                                </option>
                                <option value="Canada/Atlantic">
                                    (GMT-04:00) Atlantic Time (Canada)
                                </option>
                                <option value="America/La_Paz">
                                    (GMT-04:00) La Paz
                                </option>
                                <option value="America/Santiago">
                                    (GMT-04:00) Santiago
                                </option>
                                <option value="Canada/Newfoundland">
                                    (GMT-03:30) Newfoundland
                                </option>
                                <option value="America/Buenos_Aires">
                                    (GMT-03:00) Buenos Aires
                                </option>
                                <option value="Greenland">
                                    (GMT-03:00) Greenland
                                </option>
                                <option value="Atlantic/Stanley">
                                    (GMT-02:00) Stanley
                                </option>
                                <option value="Atlantic/Azores">
                                    (GMT-01:00) Azores
                                </option>
                                <option value="Atlantic/Cape_Verde">
                                    (GMT-01:00) Cape Verde Is.
                                </option>
                                <option value="Africa/Casablanca">
                                    (GMT) Casablanca
                                </option>
                                <option value="Europe/Dublin">
                                    (GMT) Dublin
                                </option>
                                <option value="Europe/Lisbon">
                                    (GMT) Lisbon
                                </option>
                                <option value="Europe/London">
                                    (GMT) London
                                </option>
                                <option value="Africa/Monrovia">
                                    (GMT) Monrovia
                                </option>
                                <option value="Europe/Amsterdam">
                                    (GMT+01:00) Amsterdam
                                </option>
                                <option value="Europe/Belgrade">
                                    (GMT+01:00) Belgrade
                                </option>
                                <option value="Europe/Berlin">
                                    (GMT+01:00) Berlin
                                </option>
                                <option value="Europe/Bratislava">
                                    (GMT+01:00) Bratislava
                                </option>
                                <option value="Europe/Brussels">
                                    (GMT+01:00) Brussels
                                </option>
                                <option value="Europe/Budapest">
                                    (GMT+01:00) Budapest
                                </option>
                                <option value="Europe/Copenhagen">
                                    (GMT+01:00) Copenhagen
                                </option>
                                <option value="Europe/Ljubljana">
                                    (GMT+01:00) Ljubljana
                                </option>
                                <option value="Europe/Madrid">
                                    (GMT+01:00) Madrid
                                </option>
                                <option value="Europe/Paris">
                                    (GMT+01:00) Paris
                                </option>
                                <option value="Europe/Prague">
                                    (GMT+01:00) Prague
                                </option>
                                <option value="Europe/Rome">
                                    (GMT+01:00) Rome
                                </option>
                                <option value="Europe/Sarajevo">
                                    (GMT+01:00) Sarajevo
                                </option>
                                <option value="Europe/Skopje">
                                    (GMT+01:00) Skopje
                                </option>
                                <option value="Europe/Stockholm">
                                    (GMT+01:00) Stockholm
                                </option>
                                <option value="Europe/Vienna">
                                    (GMT+01:00) Vienna
                                </option>
                                <option value="Europe/Warsaw">
                                    (GMT+01:00) Warsaw
                                </option>
                                <option value="Europe/Zagreb">
                                    (GMT+01:00) Zagreb
                                </option>
                                <option value="Europe/Athens">
                                    (GMT+02:00) Athens
                                </option>
                                <option value="Europe/Bucharest">
                                    (GMT+02:00) Bucharest
                                </option>
                                <option value="Africa/Cairo">
                                    (GMT+02:00) Cairo
                                </option>
                                <option value="Africa/Harare">
                                    (GMT+02:00) Harare
                                </option>
                                <option value="Europe/Helsinki">
                                    (GMT+02:00) Helsinki
                                </option>
                                <option value="Europe/Istanbul">
                                    (GMT+02:00) Istanbul
                                </option>
                                <option value="Asia/Jerusalem">
                                    (GMT+02:00) Jerusalem
                                </option>
                                <option value="Europe/Kiev">
                                    (GMT+02:00) Kyiv
                                </option>
                                <option value="Europe/Minsk">
                                    (GMT+02:00) Minsk
                                </option>
                                <option value="Europe/Riga">
                                    (GMT+02:00) Riga
                                </option>
                                <option value="Europe/Sofia">
                                    (GMT+02:00) Sofia
                                </option>
                                <option value="Europe/Tallinn">
                                    (GMT+02:00) Tallinn
                                </option>
                                <option value="Europe/Vilnius">
                                    (GMT+02:00) Vilnius
                                </option>
                                <option value="Asia/Baghdad">
                                    (GMT+03:00) Baghdad
                                </option>
                                <option value="Asia/Kuwait">
                                    (GMT+03:00) Kuwait
                                </option>
                                <option value="Africa/Nairobi">
                                    (GMT+03:00) Nairobi
                                </option>
                                <option value="Asia/Riyadh">
                                    (GMT+03:00) Riyadh
                                </option>
                                <option value="Europe/Moscow">
                                    (GMT+03:00) Moscow
                                </option>
                                <option value="Asia/Tehran">
                                    (GMT+03:30) Tehran
                                </option>
                                <option value="Asia/Baku">
                                    (GMT+04:00) Baku
                                </option>
                                <option value="Europe/Volgograd">
                                    (GMT+04:00) Volgograd
                                </option>
                                <option value="Asia/Muscat">
                                    (GMT+04:00) Muscat
                                </option>
                                <option value="Asia/Tbilisi">
                                    (GMT+04:00) Tbilisi
                                </option>
                                <option value="Asia/Yerevan">
                                    (GMT+04:00) Yerevan
                                </option>
                                <option value="Asia/Kabul">
                                    (GMT+04:30) Kabul
                                </option>
                                <option value="Asia/Karachi">
                                    (GMT+05:00) Karachi
                                </option>
                                <option value="Asia/Tashkent">
                                    (GMT+05:00) Tashkent
                                </option>
                                <option value="Asia/Kolkata">
                                    (GMT+05:30) Kolkata
                                </option>
                                <option value="Asia/Kathmandu">
                                    (GMT+05:45) Kathmandu
                                </option>
                                <option value="Asia/Yekaterinburg">
                                    (GMT+06:00) Ekaterinburg
                                </option>
                                <option value="Asia/Almaty">
                                    (GMT+06:00) Almaty
                                </option>
                                <option value="Asia/Dhaka">
                                    (GMT+06:00) Dhaka
                                </option>
                                <option value="Asia/Novosibirsk">
                                    (GMT+07:00) Novosibirsk
                                </option>
                                <option value="Asia/Bangkok">
                                    (GMT+07:00) Bangkok
                                </option>
                                <option value="Asia/Jakarta">
                                    (GMT+07:00) Jakarta
                                </option>
                                <option value="Asia/Krasnoyarsk">
                                    (GMT+08:00) Krasnoyarsk
                                </option>
                                <option value="Asia/Chongqing">
                                    (GMT+08:00) Chongqing
                                </option>
                                <option value="Asia/Hong_Kong">
                                    (GMT+08:00) Hong Kong
                                </option>
                                <option value="Asia/Kuala_Lumpur">
                                    (GMT+08:00) Kuala Lumpur
                                </option>
                                <option value="Australia/Perth">
                                    (GMT+08:00) Perth
                                </option>
                                <option value="Asia/Singapore">
                                    (GMT+08:00) Singapore
                                </option>
                                <option value="Asia/Taipei">
                                    (GMT+08:00) Taipei
                                </option>
                                <option value="Asia/Ulaanbaatar">
                                    (GMT+08:00) Ulaan Bataar
                                </option>
                                <option value="Asia/Urumqi">
                                    (GMT+08:00) Urumqi
                                </option>
                                <option value="Asia/Irkutsk">
                                    (GMT+09:00) Irkutsk
                                </option>
                                <option value="Asia/Seoul">
                                    (GMT+09:00) Seoul
                                </option>
                                <option value="Asia/Tokyo">
                                    (GMT+09:00) Tokyo
                                </option>
                                <option value="Australia/Adelaide">
                                    (GMT+09:30) Adelaide
                                </option>
                                <option value="Australia/Darwin">
                                    (GMT+09:30) Darwin
                                </option>
                                <option value="Asia/Yakutsk">
                                    (GMT+10:00) Yakutsk
                                </option>
                                <option value="Australia/Brisbane">
                                    (GMT+10:00) Brisbane
                                </option>
                                <option value="Australia/Canberra">
                                    (GMT+10:00) Canberra
                                </option>
                                <option value="Pacific/Guam">
                                    (GMT+10:00) Guam
                                </option>
                                <option value="Australia/Hobart">
                                    (GMT+10:00) Hobart
                                </option>
                                <option value="Australia/Melbourne">
                                    (GMT+10:00) Melbourne
                                </option>
                                <option value="Pacific/Port_Moresby">
                                    (GMT+10:00) Port Moresby
                                </option>
                                <option value="Australia/Sydney">
                                    (GMT+10:00) Sydney
                                </option>
                                <option value="Asia/Vladivostok">
                                    (GMT+11:00) Vladivostok
                                </option>
                                <option value="Asia/Magadan">
                                    (GMT+12:00) Magadan
                                </option>
                                <option value="Pacific/Auckland">
                                    (GMT+12:00) Auckland
                                </option>
                                <option value="Pacific/Fiji">
                                    (GMT+12:00) Fiji
                                </option>
                            </select>
                </div>
                <div>
                    <input type="submit" value="Login" id="loginBtn">
                </div>
                
                <div id="preloader" style="display: none;">
                    <span class="loader"></span>
                </div>
            
                <p id="error-message" style="color: red; display: none;"></p>
            </form>
            
        </div>
    </div>

<!-- <h5>Data informs,wisdom discerns.</h5> -->
    
 
<style>
    #preloader {
        backdrop-filter: blur(20px);
        z-index: 20;
        width: 100%;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #preloader .loader {
        width: 150px;
        height: 150px;
        background-image: linear-gradient(120deg, #427CE6 0%, #427CE6 100%);
        border-radius: 50%;
        position: relative;
        box-shadow: 0 0 30px 4px rgba(0, 0, 0, 0.5) inset,
            0 5px 12px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    #preloader .loader:before,
    #preloader .loader:after {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 45%;
        top: -40%;
        background-color: #fff;
        animation: wave 5s linear infinite;
    }

    #preloader .loader:before {
        border-radius: 30%;
        background: rgba(255, 255, 255, 0.4);
        animation: wave 5s linear infinite;
    }
</style>


<style>
    .image_login{
        width: 100%;
        height: 580px;
        margin: auto;
    }
</style>

<style>
    .animated-header {
        font-size: 32px; 
        font-weight: bold;
        color: #23201b; 
        animation: slideIn 2s ease-in-out; 
    }

    @keyframes slideIn {
        0% {
            opacity: 0;
            transform: translateX(-50px);
        }
        100% {
            opacity: 1;
            transform: translateX(0); 
        }
    }
</style>




  
<script>
    const loginBtn = document.getElementById('loginBtn');

    loginBtn.addEventListener('click', function () {
     
        document.getElementById('preloader').style.display = 'flex';

        
        setTimeout(() => {
           
            document.getElementById('preloader').style.display = 'none';

            const loginTime = new Date();
            const loginEntry = { type: 'login', time: loginTime };

            let logData = JSON.parse(localStorage.getItem('logData')) || [];
            logData.push(loginEntry);

            localStorage.setItem('logData', JSON.stringify(logData));
            localStorage.setItem('currentLoginTime', loginTime);

        }, 3000); 
    });
</script>
          
{{-- <script>
        const loginBtn = document.getElementById('loginBtn');
        const loginTimeDisplay = document.getElementById('login-time');

        loginBtn.addEventListener('click', function () {
            const loginTime = new Date(); 
            const loginEntry = { type: 'login', time: loginTime };

           
            let logData = JSON.parse(localStorage.getItem('logData')) || [];
            logData.push(loginEntry); 

            localStorage.setItem('logData', JSON.stringify(logData)); 
            localStorage.setItem('currentLoginTime', loginTime); 

          
            loginTimeDisplay.innerText = `Login Time: ${loginTime.toLocaleTimeString()}`;

          
            document.getElementById('logoutBtn').disabled = false;
            loginBtn.disabled = true;

            
            startHeaderTimer();
        });
    </script> --}}










    {{-- ======================================
                    SCRIPT TAGS
    ======================================= --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js" integrity="sha512-PJa3oQSLWRB7wHZ7GQ/g+qyv6r4mbuhmiDb8BjSFZ8NZ2a42oTtAq5n0ucWAwcQDlikAtkub+tPVCw4np27WCg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        window.onload = async function() {
            document.querySelector("#preloader").style.display = "none";

            async function getTimeZone()
            {
                try {
                    const clientIp = await axios.get('https://ipecho.net/plain');
                    const ipInfo = await axios.get(`http://ip-api.com/json/${clientIp.data}`)
                    const timeZone = ipInfo.data?.timezone;

                    // Unselect all
                    $('select[name=timezone]').find('option').attr('selected', false)
                    
                    $('select[name=timezone]').find(`option[value="${timeZone}"]`).attr('selected', true)

                } catch (err) {
                    console.log('Cannot getTimeZone', err.message)
                }
            }
            
            await getTimeZone();
        }
    </script>

</body>

</html>


{{-- -------------------------------------------------------------------X------------------------------------------------------ --}}

{{--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VidyaGxP - Software</title>
    <link href="https://fonts.googleapis.com/css2?family=Bitter&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Bitter', serif;
            background-image: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            margin: 0;
            padding: 0;
        }

        img {
            width: 100%;
            height: 100%;
        }

        a {
            text-decoration: none;
        }

        #preloader {
            backdrop-filter: blur(20px);
            z-index: 20;
            width: 100%;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #preloader .loader {
            width: 150px;
            height: 150px;
            background-image: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            border-radius: 50%;
            position: relative;
            box-shadow: 0 0 30px 4px rgba(0, 0, 0, 0.5) inset,
                0 5px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        #preloader .loader:before,
        #preloader .loader:after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 45%;
            top: -40%;
            background-color: #fff;
            animation: wave 5s linear infinite;
        }

        #preloader .loader:before {
            border-radius: 30%;
            background: rgba(255, 255, 255, 0.4);
            animation: wave 5s linear infinite;
        }

        @keyframes wave {
            0% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        #login-container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #login-container .login-form {
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            background: rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        #login-container .login-form .image-block {
            width: 100%;
            aspect-ratio: 1/1.3;
            height: 100%;
        }

        #login-container .login-form .form-block {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #login-container .login-form .form-block .logo-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #login-container .login-form .form-block .logo {
            width: 150px;
            margin-bottom: 20px;
        }

        #login-container .login-form .form-block .head {
            font-size: 2rem;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        #login-container .login-form .form {
            width: 100%;
            display: block;
        }

        #login-container .login-form .form-block label {
            font-size: 0.9rem;
            margin-bottom: 3px;
            color: rgb(0, 0, 0);
            display: block;
            font-weight: bold;
        }

        #login-container .login-form .form-block input,
        #login-container .login-form .form-block select {
            display: block;
            width: 100%;
            max-width: 560px;
            height: 100%;
            margin-bottom: 20px;
            font-size: 0.9rem;
            padding: 3px 0px;
            max-height: 32px;
            background: white;
        }

        #login-container .login-form .form-block input[type="submit"] {
            margin-bottom: 0;
            background: #4274da;
            border: 1px solid #4274da;
            color: white;
            font-weight: bold;
            padding: 7px 10px;
            transition: all 0.3s linear;
            height: auto;
            font-family: 'Cinzel', serif;
            text-transform: uppercase;
            cursor: pointer;
            letter-spacing: 1px;
        }

        #login-container .login-form .form-block input[type="submit"]:hover {
            letter-spacing: 4px;
        }

        #login-container .login-form .form-block .forgot {
            font-size: 0.85rem;
            color: black;
            display: block;
            margin-bottom: 10px;
            text-align: center;
            max-width: 560px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .skiptranslate.goog-te-gadget {
            height: 34px;
            margin-bottom: 20px;
            overflow: hidden;
        }
    </style>
</head>

<body>

    ======================================
                    PRELOADER
    =======================================
    <div id="preloader">
        <span class="loader"></span>
    </div>

    ======================================
                    LOGIN FORM
    =======================================
     <div id="login-container">
        <div class="login-form">
            <div class="image-block">
                <img src="{{ asset('user/images/login.jpg') }}" alt="..." class="w-100 h-100">
            </div>
            <div class="form-block">
                <div class="logo-container11">
                    <div class="logo">
                        <img src="{{ asset('user/images/logo.png') }}" alt="..." class="w-100 h-100">
                    </div>
                    <div class="logo">
                        <img src="{{ asset('user/images/logo1.png') }}" alt="..." class="w-100 h-100">
                    </div>
                </div>
                <div class="head">
                    Welcome to DMS
                </div>
                <div class="form">
                    <form action="{{ url('logincheck') }}" method="POST">
                        @csrf
                        <div class="group-input">
                            <label for="username">Username</label>
                            <input type="text" name="email">
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span> <br> <br>
                        @enderror

                        <div class="group-input">
                            <label for="password">Password</label>
                            <input type="password" name="password">
                        </div>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            <br><br>
                        @enderror

                        <div class="group-input">
                            <label for="language">Language</label>
                            <select class="goog-te-combo">
                                <option value="">Select Language</option>
                                <option value="af">Afrikaans</option>
                                <option value="sq">Albanian</option>
                                <option value="ar">Arabic</option>
                                <option value="hy">Armenian</option>
                                <option value="az">Azerbaijani</option>
                                <option value="eu">Basque</option>
                                <option value="be">Belarusian</option>
                                <option value="bn">Bengali</option>
                                <option value="bg">Bulgarian</option>
                                <option value="ca">Catalan</option>
                                <option value="zh-CN">Chinese (Simplified)</option>
                                <option value="zh-TW">Chinese (Traditional)</option>
                                <option value="hr">Croatian</option>
                                <option value="cs">Czech</option>
                                <option value="da">Danish</option>
                                <option value="nl">Dutch</option>
                                <option value="en" selected>English</option>
                                <option value="eo">Esperanto</option>
                                <option value="et">Estonian</option>
                                <option value="tl">Filipino</option>
                                <option value="fi">Finnish</option>
                                <option value="fr">French</option>
                                <option value="gl">Galician</option>
                                <option value="ka">Georgian</option>
                                <option value="de">German</option>
                                <option value="el">Greek</option>
                                <option value="gu">Gujarati</option>
                                <option value="ht">Haitian Creole</option>
                                <option value="iw">Hebrew</option>
                                <option value="hi">Hindi</option>
                                <option value="hu">Hungarian</option>
                                <option value="is">Icelandic</option>
                                <option value="id">Indonesian</option>
                                <option value="ga">Irish</option>
                                <option value="it">Italian</option>
                                <option value="ja">Japanese</option>
                                <option value="kn">Kannada</option>
                                <option value="ko">Korean</option>
                                <option value="la">Latin</option>
                                <option value="lv">Latvian</option>
                                <option value="lt">Lithuanian</option>
                                <option value="mk">Macedonian</option>
                                <option value="ms">Malay</option>
                                <option value="mt">Maltese</option>
                                <option value="no">Norwegian</option>
                                <option value="fa">Persian</option>
                                <option value="pl">Polish</option>
                                <option value="pt">Portuguese</option>
                                <option value="ro">Romanian</option>
                                <option value="ru">Russian</option>
                                <option value="sr">Serbian</option>
                                <option value="sk">Slovak</option>
                                <option value="sl">Slovenian</option>
                                <option value="es">Spanish</option>
                                <option value="sw">Swahili</option>
                                <option value="sv">Swedish</option>
                                <option value="ta">Tamil</option>
                                <option value="te">Telugu</option>
                                <option value="th">Thai</option>
                                <option value="tr">Turkish</option>
                                <option value="uk">Ukrainian</option>
                                <option value="ur">Urdu</option>
                                <option value="vi">Vietnamese</option>
                                <option value="cy">Welsh</option>
                                <option value="yi">Yiddish</option>
                            </select>
                        </div>

                        <div class="group-input">
                            <label for="timezone">Time Zone</label>
                            <select name="timezone">
                                @foreach ($timezones as $key => $value)
                                    <option value="{{ $key }}" {{ $key == 'Asia/Kolkata' ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="forgot">
                            <a href="forgot-password">Forgot Password</a>
                        </div>

                        <div class="group-input">
                            <input type="submit" value="Login">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>



     ======================================
                    SCRIPT TAGS
    =======================================
     <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script>
        function googleTranslateElementInit() {
            setCookie('googtrans', '/en/pt', 1);
            new google.translate.TranslateElement({
                pageLanguage: 'en'
            }, 'google_translate_element');
        }

        window.onload = function() {
            document.querySelector("#preloader").style.display = "none";
        }
    </script>

    @toastr_js
    @toastr_render
    @jquery

</body>

</html>  --}}
