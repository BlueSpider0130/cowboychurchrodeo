<button class="btn " 
    style="position:fixed;
        width:38px;
        height:38px;
        border-radius: 19px;
        bottom: 20px;
        right:20px;

        box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;" 
        data-toggle="modal" 
        data-target="#feedback-modal">
    <i class="far fa-comments"></i>
</button>


<!-- Modal -->
<div class="modal fade" id="feedback-modal" tabindex="-1" role="dialog" aria-labelledby="feedback-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="feedback-modal-title"> Feedback </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="feedback-form">
                    <p> Please leave feedback concerning this page. </p>

                    <b>Comment:</b>
                    <form>
                        @csrf
                        <textarea name="comment" id="comment" class="form-control" rows="10"></textarea>
                    </form>
                </div>
            </div>

            <div class="modal-footer" id="feedback-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel </button>
                <button type="button" class="btn btn-primary" onclick="submitFeedback();"> Submit feedback </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="feedback-success-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="feedback-success-modal-submitting-message" class="text-danger">
                    Submitting feedback...
                </div>
                <div id="feedback-success-modal-submitted-message" style="display: none;">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        Feedback submitted
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('body')
<script>
    function submitFeedback() {
        $("#feedback-modal").modal("hide");
        $("#feedback-success-modal").modal("show");
        document.getElementById('feedback-success-modal-submitting-message').style.display = "block";
        document.getElementById('feedback-success-modal-submitted-message').style.display = "none";

        const params = {
            _token: document.getElementsByName('_token')[0].value,
            page: window.location.pathname,
            comment: document.getElementsByName('comment')[0].value
        }

        const XHR = new XMLHttpRequest()
        XHR.open('POST', '/feedback')
        XHR.setRequestHeader('Content-type', 'application/json')
        XHR.send(JSON.stringify(params))

        XHR.onload = function() {
            document.getElementById('comment').value = '';
            document.getElementById('feedback-success-modal-submitting-message').style.display = "none";
            document.getElementById('feedback-success-modal-submitted-message').style.display = "block";
        }
    }
</script>
@endpush