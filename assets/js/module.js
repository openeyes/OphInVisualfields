
/* Module-specific javascript can be placed here */

$(document).ready(function() {
  
  $('#div_Element_OphInVisualfields_Condition_other').hide();
  
  $('#Element_OphInVisualfields_Condition_ability_id').change(function() {
    if ($('#Element_OphInVisualfields_Condition_ability_id option:selected').text() == 'Other') {
      $('#div_Element_OphInVisualfields_Condition_other').show();      
    } else {
      $('#div_Element_OphInVisualfields_Condition_other').hide();      
    }
  });
  $('#div_Element_OphInVisualfields_Result_other').hide();
  
  $('#Element_OphInVisualfields_Result_assessment_id').change(function() {
    if ($('#Element_OphInVisualfields_Result_assessment_id option:selected').text() == 'Other') {
      $('#div_Element_OphInVisualfields_Result_other').show();      
    } else {
      $('#div_Element_OphInVisualfields_Result_other').hide();      
    }
  });
  
  $('#et_save').unbind('click').click(function() {
    if (!$(this).hasClass('inactive')) {
      disableButtons();

			
      return true;
    }
    return false;
  });

  $('#et_cancel').unbind('click').click(function() {
    if (!$(this).hasClass('inactive')) {
      disableButtons();

      if (m = window.location.href.match(/\/update\/[0-9]+/)) {
        window.location.href = window.location.href.replace('/update/','/view/');
      } else {
        window.location.href = baseUrl+'/patient/episodes/'+et_patient_id;
      }
    }
    return false;
  });

  $('#et_deleteevent').unbind('click').click(function() {
    if (!$(this).hasClass('inactive')) {
      disableButtons();
      return true;
    }
    return false;
  });

  $('#et_canceldelete').unbind('click').click(function() {
    if (!$(this).hasClass('inactive')) {
      disableButtons();

      if (m = window.location.href.match(/\/delete\/[0-9]+/)) {
        window.location.href = window.location.href.replace('/delete/','/view/');
      } else {
        window.location.href = baseUrl+'/patient/episodes/'+et_patient_id;
      }
    } 
    return false;
  });

  $('select.populate_textarea').unbind('change').change(function() {
    if ($(this).val() != '') {
      var cLass = $(this).parent().parent().parent().attr('class').match(/Element.*/);
      var el = $('#'+cLass+'_'+$(this).attr('id'));
      var currentText = el.text();
      var newText = $(this).children('option:selected').text();

      if (currentText.length == 0) {
        el.text(ucfirst(newText));
      } else {
        el.text(currentText+', '+newText);
      }
    }
  });
});

function ucfirst(str) {
  str += '';
  var f = str.charAt(0).toUpperCase();
  return f + str.substr(1);
}

function eDparameterListener(_drawing) {
  if (_drawing.selectedDoodle != null) {
  // handle event
  }
}
