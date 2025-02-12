<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Supplier Single Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"
        integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>

    <iframe src="{{ route('regulatorySingleReport', $id) }}" style="width: 100%; height: 100vh;"
        frameborder="0"></iframe>

    {{-- CHAT PDF MODAL START --}}
    <button style="position: sticky; bottom: 1rem; left: 95%;" type="button"
        class="btn bg-theme rounded-circle shadow-lg" data-bs-toggle="modal" data-bs-target="#chatModal">
        <!--<img src="{{ asset('user/images/ai_2814666.png') }}" alt="chatWithDoc" class="img-fluid" style="width: 2rem; height: 2.4rem;">-->
        <img src="https://cdn.dribbble.com/users/1523313/screenshots/16134521/media/3975730626bdae63cf9b25d3b634bac3.gif"
            alt="chatWithDoc" class="img-fluid" style="width: 5.2rem; height: 5rem; border-radius: 100px;">
    </button>

    <div class="modal border-0" tabindex="-1" id="chatModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding: 0 0 0 11px;">
                    <div class="row w-100">
                        <div class="col-2 bg-theme d-flex justify-content-center align-items-center text-white"
                            style="border-radius: 0.5rem 0 0;">
                            <img src="https://cdn.dribbble.com/users/1523313/screenshots/16134521/media/3975730626bdae63cf9b25d3b634bac3.gif"
                                alt="chatWithDoc" class="img-fluid"
                                style="width: 3rem; height: 3rem; border-radius: 100px;">

                        </div>
                        <div class="col-9">
                            <h5 class="modal-title fs-6" style="padding: 0.5rem;">
                                Supercharge PDF Conversations: Seamlessly Engage with Your PDFs!</h5>
                        </div>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="margin-right: 0.8rem;"></button>
                </div>
                <div class="modal-body">
                    <div class="chat-content" id="chatContent" style="height: 15rem; overflow-y: scroll;">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <textarea type="text" name="chatSendMessage" id="" placeholder="Enter your message here"
                            class="form-control shadow-md border-0"></textarea>
                    </div>
                    <div class="row w-100">
                        <div class="col-6">
                            <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">Close</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn bg-dark text-white w-100" id="sendChatBtn">
                                <div class="spinner-border spinner-border-sm text-light" role="status"
                                    style="display: none">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- CHAT PDF MODAL END --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"
        integrity="sha512-PJa3oQSLWRB7wHZ7GQ/g+qyv6r4mbuhmiDb8BjSFZ8NZ2a42oTtAq5n0ucWAwcQDlikAtkub+tPVCw4np27WCg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            const config = {
                headers: {
                    "x-api-key": "sec_qLUcsYBeIWAt564Tk5zhHg76DQHjastL",
                    "Content-Type": "application/json",
                },
            };

            function scrollToBottom() {
                var chatContent = $('#chatContent');
                chatContent.animate({
                    scrollTop: chatContent.prop('scrollHeight')
                }, 500);
            }

            const data = {
                url: "{{ asset('user/pdf/reg/reg' . $data->id . '.pdf') }}"
            }

            let srcId = "";

            function getUserMessageHtml(message) {
                let html =
                    `<div class="chat-content-user-chat bg-dark text-white p-3 rounded-3 w-50 mb-2" style="margin-left: auto;">${message}</div>`;
                return html;
            }

            function getResponseMessageHtml(message) {
                let html =
                    `<div class="chat-content-ai-chat bg-light p-3 rounded-3 w-50 mb-2" style="margin-right: auto;">${message}</div>`;
                return html;
            }

            async function initializeChatModal() {
                console.log('initializeChatModal')
                try {
                    const addPdfUrl = "https://api.chatpdf.com/v1/sources/add-url";

                    const res = await axios.post(addPdfUrl, data, config)

                    console.log('res', res);

                    srcId = res.data.sourceId;

                } catch (err) {
                    console.log('Error in initializeChatModal fn', err.message)
                }
            }

            async function sendChat() {

                let message = $('textarea[name=chatSendMessage]').val();

                if (message && message.trim() !== '') {
                    scrollToBottom()

                    $('#sendChatBtn').prop('disabled', true);
                    $('#sendChatBtn > i').hide();
                    $('#sendChatBtn > div').show();

                    const chatData = {
                        "sourceId": srcId,
                        "messages": [{
                            "role": "user",
                            "content": message
                        }]
                    }

                    const userMsgHtml = getUserMessageHtml(message);
                    $('#chatContent').append(userMsgHtml).show('slow');
                    $('textarea[name=chatSendMessage]').val('');

                    try {

                        const chatPdfEndpoint = "https://api.chatpdf.com/v1/chats/message";

                        const res = await axios.post(chatPdfEndpoint, chatData, config)

                        console.log('res', res);

                        let resMsg = res.data.content;

                        const aiResHtml = getResponseMessageHtml(resMsg);
                        $('#chatContent').append(aiResHtml).show('slow');

                    } catch (err) {
                        console.log('Error in sendChat fn', err.message)
                    }
                    $('#sendChatBtn').prop('disabled', false);
                    $('#sendChatBtn > i').show();
                    $('#sendChatBtn > div').hide();

                    scrollToBottom()
                }


            }

            initializeChatModal();

            $('#sendChatBtn').click(function() {
                sendChat();
            })
        })
    </script>




    <style>
        .group-input input {
            width: 60%;
        }
    </style>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            var pdfObject = document.querySelector('iframe#theFrame"]');
            var pdfDocument = pdfObject.contentDocument;
            var firstPage = pdfDocument.querySelector('.page:first-of-type');
            firstPage.style.display = 'none';
        });
    </script>

</body>

</html>
