$(document).ready(function() {
    function getCsrf() {
        return $('meta[name="csrf-token"]').attr('content');
    }

    //sublocations
    $('.show-sublocation').on('click', function() {
        $this = $(this);
        $.post('/admin/podloc/show-sublocation/' + $this.data('id'), {
            '_token': getCsrf()
        }, function() {
           $this.attr('disabled', 'disabled');
        });
    });
});