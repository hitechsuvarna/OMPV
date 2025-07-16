var interval = 3000;

function blink() {
  $("#eyeL").playKeyframe({
    name: 'eyeLblink',
    duration: '200ms',
    timingFunction: 'linear',
    complete: function(){$("#eyeL").resetKeyframe();}
  });
  $("#eyeR").playKeyframe({
    name: 'eyeRblink',
    duration: '200ms',
    timingFunction: 'linear',
    complete: function(){$("#eyeR").resetKeyframe();}
  });

  interval = Math.random() * (6000 - 3000) + 3000;
  setTimeout(blink, interval);
}

setTimeout(blink, interval);

$(".smiley").click(function() {
  $("#eyeL").pauseKeyframe();
  $("#eyeR").pauseKeyframe();
  $(".smiley").playKeyframe({
    name: 'roll',
    duration: '700ms',
    timingFunction: 'ease',
    complete: function(){
      $(".smiley").resetKeyframe();
      $("#eyeL").resumeKeyframe();
      $("#eyeR").resumeKeyframe();
    }
  });
}).children(".eye").click(function(e) {
  return false;
});

$("#eyeL").click(function() {
  $("#eyeL").playKeyframe({
    name: 'eyeLblink',
    duration: '200ms',
    timingFunction: 'linear',
    complete: function(){$("#eyeL").resetKeyframe();}
  });
});

$("#eyeR").click(function() {
  $("#eyeR").playKeyframe({
    name: 'eyeRblink',
    duration: '200ms',
    timingFunction: 'linear',
    complete: function(){$("#eyeR").resetKeyframe();}
  });
});
