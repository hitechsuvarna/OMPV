var supportedFlag = $.keyframe.isSupported();

$.keyframe.define([
  {
    name: 'eyeLblink',
    '0%': {'height': '50px', 'margin-top': '10px'},
    '50%': {'height': '2px', 'width': '70px', 'margin-top': '30px'},
    '100%': {'height': '50px', 'margin-top': '10px'}
  },
  {
    name: 'eyeRblink',
    '0%': {'height': '50px', 'margin-top': '10px'},
    '50%': {'height': '2px', 'width': '70px', 'margin-top': '30px'},
    '100%': {'height': '50px',  'margin-top': '10px'}
  },
  {
    name: 'roll',
    '0%': {'transform': 'rotate(0deg)'},
    '25%': {'transform': 'rotate(40deg)'},
    '66%': {'transform': 'rotate(-40deg)'},
    '90%': {'transform': 'rotate(10deg)'},
    '100%': {'transform': 'rotate(0deg)'}
  }
]);
