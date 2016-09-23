var itemIdx = 0;

function log(text, data) {
   if (data) {
      text += ' ' + JSON.stringify(data);
   }

   console.log(text, data);

   $('#log').append('<p>' + text + '</p>')
}

function updateDataByType(data, type) {
   if (type === 'call') {
      data.type = $('#call-type').val();
   }

   if (type === 'meeting') {
      data.type = $('#result').is(':checked');
   }

   if (type === 'sale') {
      data.items = [];
      $('div.sale .row').each(function() {
         var $this = $(this);
         data.items.push({
            cost:    $this.find('.sale-item-cost').val(),
            amount:  $this.find('.sale-item-amount').val(),
            service: $this.find('.sale-item-service').val()
         });
      });
   }

   return data;
}

function getSaleItemRow() {
   itemIdx++;

   return '<div class="row">'
      +   '<div class="col-xs-1">'
      +     '<a href="#" class="btn btn-link sale-item-delete"><i class="glyphicon glyphicon-minus" style="width: 100%;" aria-hidden="true"></i></a>'
      +   '</div>'
      +   '<div class="col-xs-11">'
      +     '<div class="form-group">'
      +       '<label for="service' + itemIdx + '">Service</label>'
      +       '<input type="text" class="form-control" id="service' + itemIdx + '" class="sale-item-service" placeholder="Some service" required>'
      +     '</div>'
      +     '<div class="form-group">'
      +       '<label for="amount' + itemIdx + '">Amount</label>'
      +       '<input type="number" class="form-control" id="amount' + itemIdx + '" class="sale-item-amount" min="1" placeholder="Amount, e.g. 1" required>'
      +     '</div>'
      +     '<div class="form-group">'
      +       '<label for="cost' + itemIdx + '">Cost</label>'
      +       '<input type="number" class="form-control" id="cost' + itemIdx + '" class="sale-item-cost" min="1" placeholder="Cost, e.g. 100" required>'
      +     '</div>'
      +   '</div>'
      + '</div>'
   ;
}

$(function() {
   var $form = $('#add-event-form');

   $form.submit(function() {
      var type = $('#type').val();
      var data = {
         phone: $('#users').val()
      };

      data = updateDataByType(data, type);

      log('send request', data);
      $.ajax({
         type: 'POST',
         url: '/api/v1/events/' + type,
         data: JSON.stringify(data),
         dataType: 'json',
         success: function (data, status) {
            log('success', data);
         },
         error: function(e) {
            log('error', e);
         }
      });

      return false;
   });

   $('#type').change(function() {
      var value = $(this).val();

      $form.find('div.form-group.call').hide();
      $form.find('div.form-group.sale').hide();
      $form.find('div.form-group.meeting').hide();

      $form.find('div.form-group.' + value).show();
   });

   $('.sale-item-add').click(function() {

      $(getSaleItemRow()).insertBefore($(this));
      return false;
   });

   $(document).on('click', '.sale-item-delete', function() {
      $(this).closest('div.row').remove();

      return false;
   });
});
