


    $(document).ready(function() {
  var increment = 0
  var $html = $("html")

  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
  var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
    var popover = new bootstrap.Popover(popoverTriggerEl, {
      container: 'body',
      html: true,
      trigger: 'manual'
    });
    popoverTriggerEl.addEventListener('mouseover', (event) => {
      var $thisElement = $(event.target)
      if (($thisElement.attr("class") || "").includes("keep-popover-")) { // no repeat event
        return
      }

      popover.show();
      var popoverId = $(popover.tip).attr("id")

      var className = `keep-popover-${++increment}`
      $thisElement.addClass(className)
      const debouncedClosePopover = debounce(closePopover, 250)

      $html.on("mouseover mouseout", debouncedClosePopover)

      function closePopover(event) {
        var $target = $(event.target),
          keepPopover = $target.is(`.${className}, .${className} *, #${popoverId}, #${popoverId} *`)

        if (!keepPopover) {
          popover.hide()
          $thisElement.removeClass(className)
          $html.off("mouseover mouseout", debouncedClosePopover)
        }
      }
    });
    return popover;
  });

});

function debounce(fn, delay) {
  let mainIndex = 0

  return (...args) => {
    mainIndex++
    const currentIndex = mainIndex
    setTimeout(() => {
      if (currentIndex === mainIndex) {
        fn(...args)
      }
    }, delay)
  }
}