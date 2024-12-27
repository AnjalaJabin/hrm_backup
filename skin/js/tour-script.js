$(function() {
  
  // define tour
  var tour = new Tour({
    debug: true,
    placement: 'top',
    basePath: location.pathname.slice(0, location.pathname.lastIndexOf('/')),
    steps: [{
      path: "/settings",
      element: "#settings_tour",
      title: "Title of my step",
      content: "Content of my step"
    }, {
      path: "/settings",
      element: "#settings_menu_tour",
      title: "Title of my step",
      content: "Content of my step"
    }, {
      path: "/settings",
      element: "#add_new_employee_tour",
      title: "Title of my step",
      content: "Content of my step"
    }]
  });

  // init tour
  tour.init();

  // start tour
  tour.start();
  
  $('#start_tour').on('click', function(){ tour.restart(); });
  
});