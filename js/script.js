$(function(){

  $('[data-toggle="tooltip"]').tooltip();

  $('.sort-bar .search form').on('submit', function(e){
    e.preventDefault();
    var $this = $(this),
      action = $this.attr('action'),
      keyword = $this.find('input').val()
    ;

    if( !keyword.length ) return;

    window.location.href = window.location.origin+action+keyword;
  });

  $('#delete-modal').on('show.bs.modal', function(event){
    var $button = $(event.relatedTarget);
    var url = $button.data('url');
    $(this).find('#ok').attr({href:url});
  });
});
