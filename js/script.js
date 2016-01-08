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
});
