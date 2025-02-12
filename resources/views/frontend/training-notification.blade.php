@extends('frontend.layout.main')
@section('container')
    <section id="manual-notification">
        <div class="container-fluid">
            <div class="inner-block">
                <div class="main-head">
                    Record #{{ $document->id }} - {{ $document->traning_plan_name }}
                </div>
                <div class="inner-block-content">
                    <div class="details">
                            {{-- <div>
                                <strong>Division/Project : </strong>
                                QMS - North America / Change Control
                            </div> --}}
                            <div>
                            <strong>Record State : </strong>
                            {{ $document->status }}
                        </div>
                        <div>
                            <strong>Assigned To : </strong>
                            {{ $document->trainner_id->name }}
                        </div>
                        <div>
                            <form action="{{ url('send-notification') }}" method="POST">
                                @csrf
                            <strong>Recipents - Add : </strong>

                            <div class="search-input">
                                <select name="option" id="my-select">
                                    <option value="0">-- Select Recipent</option>
                                    <option value="{{ $document->trainner_id->id }}"> {{ $document->trainner_id->name }}</option>
                                    @foreach ($document->trainees as $value)
                                    @php
                                        $value = DB::table('users')->where('id',$value)->first();
                                    @endphp
                                    <option value="{{ $value->id }}"> {{ $value->name }}</option>
                                    @endforeach
                                </select>
                                {{-- <label for="recipent">Add</label> --}}
                            </div>
                        </div>
                    </div>
                    <div class="recipent-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Recipent</th>
                                    <th>Relationship</th>
                                    <th>Method</th>

                                </tr>
                            </thead>
                            <tbody id="my-table-body">

                            </tbody>
                        </table>
                    </div>
                    <div class="summary">
                        <div class="group-input">
                            <label for="summary">Notification Summary</label>
                            <textarea name="summary"></textarea>

                        </div>
                        <div class="group-input">
                            <label for="summary">Attech file</label>
                            <input type="file" name="file">
                        </div>
                    </div>
                    <div class="noti-btns">
                        <button type="submit">Send</button>
                        <button>Cancel</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('#my-select').change(function() {
                var selectedOption = $(this).val();

                // Send an AJAX request to fetch the data for the selected option
                $.ajax({
                    url: '/get-data',
                    type: 'GET',
                    data: { option: selectedOption },

                    success: function(response) {

                        // Update the table with the selected data
                        $('#my-table-body').html(`
                            <tr>
                                <th>${response.name}<input type="hidden" value="${response.name}"></th>
                                <th>${response.role}<input type="hidden" value="${response.role}"></th>
                                <th>
                                        <select name="method">
                                            <option>-- Select --</option>
                                            <option value="email">E-Mail</option>
                                        </select>
                                    </th>
                            </tr>
                        `);
                    },
                    error: function(xhr, status, error) {
                    }
                });
            });
        });
    </script>
@endsection
