$('#get-urls').on('click', function(e){
  var xhr = $.get('/buildlist');
  $(this).addClass('active').attr('data-aria-pressed', 'true');
  xhr.success(function(data){
    var str = JSON.parse(data);
    $('#viewer').html('<div id="viewer">' + DumpObjectIndented(str) + '</div>');
    $('#check-all-urls').addClass('btn-primary').removeClass('btn-secondary');
    $('#run-all-urls').addClass('btn-danger').removeClass('btn-secondary');
  });
});

$('#check-all-urls').on('click', function(e){
  $(this).addClass('active');
  checkUrl($('#viewer .line:not(.done)').first());
});

$(document).on('checkNextUrl', function(e)
{
  checkUrl($('#viewer .line:not(.done)').first());
});

$('#run-all-urls').on('click', function(e){
  $(this).addClass('active');
  $('#check-all-urls').removeClass('btn-primary').addClass('btn-secondary');
  checkUrl($('#viewer .line:not(.done)').first(), true);
});

$(document).on('runNextUrl', function(e)
{
  checkUrl($('#viewer .line:not(.done)').first(), true);
});
var errorUrls = [];
function checkUrl(element, writetofile)
{
  var target = element.text().split(' : ')[1].replace("'", "").replace("',", "");
  $.post('/runsingle', { url: target }).done(function(data){
    element.addClass('done');
    $.event.trigger('runNextUrl');
    data = JSON.parse(data);
    if(data.success == true)
    {
      element.addClass('success');
    } else if(data.success == 'warning') {
      element.addClass('warning');
      element.append('<span class="warning">Check manually!</span>');
    } else {
      element.addClass('error');
      element.append('<div class="error-msg">' + JSON.stringify(data) + '</div>');
      if(writetofile)
      {
        errorUrls.push(target);

        $.post('/cleanupfile', { url: target }).done(function(data){
          console.log(target + ' - has been deleted as redirect!');
        });
      }
    }
    return data;
  });
}


function DumpObjectIndented(obj, indent)
{
  var result = "";
  if (indent == null) indent = "";

  for (var property in obj)
  {
    var value = obj[property];
    if (typeof value == 'string')
      value = "'" + value + "'";
    else if (typeof value == 'object')
    {
      if (value instanceof Array)
      {
        // Just let JS convert the Array to a string!
        value = "[ " + value + " ]";
      }
      else
      {
        // Recursive dump
        // (replace "  " by "\t" or something else if you prefer)
        var od = DumpObjectIndented(value, indent + "  ");
        // If you like { on the same line as the key
        //value = "{\n" + od + "\n" + indent + "}";
        // If you prefer { and } to be aligned
        value = "\n" + indent + "{\n" + od + "\n" + indent + "}";
      }
    }
    result += "<span class='line'><span class='string'>" + indent + "'" + property + "' : " + value + "</span><span class='comma'>,</span></span>\n";
  }
  return result.replace(/,\n$/, "");
}
