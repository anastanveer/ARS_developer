(function ($) {
  "use strict";

  var isMobileDevice = window.matchMedia("(max-width: 767px)").matches;
  var prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var saveDataEnabled = !!(navigator.connection && navigator.connection.saveData);
  var lowEndDevice = !!(navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 4);
  var lowPowerMode = isMobileDevice || prefersReducedMotion || saveDataEnabled || lowEndDevice;


  if (typeof Swiper !== "undefined" && document.querySelector(".swiper")) {
    var isMobilePortfolio = isMobileDevice;

    new Swiper(".swiper", {
      effect: isMobilePortfolio ? "slide" : "coverflow",
      grabCursor: true,
      centeredSlides: !isMobilePortfolio,
      slidesPerView: 1,
      coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 100,
        modifier: 4,
        slideShadows: true
      },
      loop: true,
      // Navigation arrows
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev"
      },
      keyboard: {
        enabled: true
      },
      mousewheel: {
        thresholdDelta: 70
      },
      breakpoints: {
        768: {
          slidesPerView: 1.5
        },
        992: {
          slidesPerView: 2.5
        },
        1290: {
          slidesPerView: 3
        }
      }
    });
  }






  /*--------------------------------------------------------------
    RegisterPlugin, ScrollTrigger, SplitText
  --------------------------------------------------------------*/
  if (typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined" && typeof SplitText !== "undefined") {
    gsap.registerPlugin(ScrollTrigger, SplitText);
    gsap.config({
      nullTargetWarn: false,
      trialWarn: false
    });
  }



  // Preloader
  $(function () {
    var $preloader = $('.js-preloader');
    if ($preloader.length) {
      setTimeout(function () {
        $preloader.fadeOut(120);
      }, 180);
    }
  });

  $(window).on('load', function (event) {
    $('.js-preloader').delay(0).fadeOut(220);
  });


  // AOS Animation
  if ($("[data-aos]").length) {
    AOS.init({
      duration: '1200',
      disable: lowPowerMode,
      easing: 'ease',
      mirror: !lowPowerMode,
      once: lowPowerMode
    });
  }



  /*--------------------------------------------------------------
    FullHeight
  --------------------------------------------------------------*/
  function fullHeight() {
    $('.full-height').css("height", $(window).height());
  }



  // Price Filter
  function priceFilter() {
    if ($(".price-ranger").length) {
      $(".price-ranger #slider-range").slider({
        range: true,
        min: 50,
        max: 500,
        values: [11, 300],
        slide: function (event, ui) {
          $(".price-ranger .ranger-min-max-block .min").val(
            "$" + ui.values[0]
          );
          $(".price-ranger .ranger-min-max-block .max").val(
            "$" + ui.values[1]
          );
        },
      });
      $(".price-ranger .ranger-min-max-block .min").val(
        "$" + $(".price-ranger #slider-range").slider("values", 0)
      );
      $(".price-ranger .ranger-min-max-block .max").val(
        "$" + $(".price-ranger #slider-range").slider("values", 1)
      );
    }
  }




  $(".add").on("click", function () {
    if ($(this).prev().val() < 999) {
      $(this)
        .prev()
        .val(+$(this).prev().val() + 1);
    }
  });
  $(".sub").on("click", function () {
    if ($(this).next().val() > 1) {
      if ($(this).next().val() > 1)
        $(this)
        .next()
        .val(+$(this).next().val() - 1);
    }
  });




  // ===Checkout Payment===
  if ($(".checkout__payment__title").length) {

    $(".checkout__payment__item").find('.checkout__payment__content').hide();
    $(".checkout__payment__item--active").find('.checkout__payment__content').show();

    $(".checkout__payment__title").on("click", function (e) {
      e.preventDefault();


      $(this).parents('.checkout__payment').find('.checkout__payment__item').removeClass("checkout__payment__item--active");
      $(this).parents(".checkout__payment").find(".checkout__payment__content").slideUp();

      $(this).parent().addClass("checkout__payment__item--active");
      $(this).parent().find(".checkout__payment__content").slideDown();

    })
  }






  // Type Effect
  if ($('.typed-effect').length) {
    $('.typed-effect').each(function () {
      var typedStrings = $(this).data('strings');
      var typedTag = $(this).attr('id');
      var typed = new Typed('#' + typedTag, {
        typeSpeed: 100,
        backSpeed: 100,
        fadeOut: true,
        loop: true,
        strings: typedStrings.split(',')
      });
    });

  }








  if ($("#switch-toggle-tab").length) {
    var pricingTabs = $("#switch-toggle-tab .pricing-one__tab-btn");
    var monthTabContent = $("#month");
    var yearTabContent = $("#year");

    function setPricingTab(activeTab) {
      var showMonth = activeTab === "month";
      pricingTabs.removeClass("active").attr("aria-selected", "false").attr("tabindex", "-1");

      var activeTabBtn = pricingTabs.filter('[data-pricing-target="' + (showMonth ? "month" : "year") + '"]');
      activeTabBtn.addClass("active").attr("aria-selected", "true").attr("tabindex", "0");

      monthTabContent.stop(true, true)[showMonth ? "fadeIn" : "fadeOut"](160).attr("aria-hidden", showMonth ? "false" : "true");
      yearTabContent.stop(true, true)[showMonth ? "fadeOut" : "fadeIn"](160).attr("aria-hidden", showMonth ? "true" : "false");
    }

    pricingTabs.on("click", function (e) {
      e.preventDefault();
      setPricingTab($(this).data("pricing-target") === "year" ? "year" : "month");
    });

    pricingTabs.on("keydown", function (e) {
      if (e.key !== "ArrowRight" && e.key !== "ArrowLeft") return;
      e.preventDefault();
      var currentIndex = pricingTabs.index(this);
      var nextIndex = e.key === "ArrowRight" ? currentIndex + 1 : currentIndex - 1;
      if (nextIndex < 0) nextIndex = pricingTabs.length - 1;
      if (nextIndex >= pricingTabs.length) nextIndex = 0;
      var nextTab = pricingTabs.eq(nextIndex);
      nextTab.trigger("focus");
      setPricingTab(nextTab.data("pricing-target") === "year" ? "year" : "month");
    });

    setPricingTab(pricingTabs.filter(".active").data("pricing-target") === "year" ? "year" : "month");
  }








  //Main Slider 
  if ($(".main-slider__carousel").length) {
    $(".main-slider__carousel").owlCarousel({
      loop: false,
      animateOut: "fadeOut",
      animateIn: "fadeIn",
      margin: 0,
      nav: false,
      dots: false,
      smartSpeed: 500,
      autoplay: false,
      autoplayTimeout: 0,
      mouseDrag: false,
      touchDrag: false,
      pullDrag: false,
      freeDrag: false,
      navText: [
        '<span class="icon-right-arrow-1"></span>',
        '<span class="icon-right-arrow-1"></span>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        600: {
          items: 1,
        },
        800: {
          items: 1,
        },
        992: {
          items: 1,
        },
      },
    });
  }










  //Brand One Carousel
  if ($(".brand-one__carousel").length) {
    $(".brand-one__carousel").owlCarousel({
      loop: true,
      margin: 30,
      nav: false,
      dots: false,
      smartSpeed: 500,
      autoplay: !lowPowerMode,
      autoplayTimeout: 7000,
      navText: [
        '<span class="icon-left-arrow"></span>',
        '<span class="icon-next"></span>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        540: {
          items: 2,
        },
        768: {
          items: 2,
        },
        992: {
          items: 3,
        },
        1200: {
          items: 4,
        },
        1320: {
          items: 4,
        },
      },
    });
  }







  //Portfolio One Carousel
  if ($(".portfolio-one__carousel").length) {
    $(".portfolio-one__carousel").owlCarousel({
      loop: true,
      margin: 0,
      nav: true,
      dots: false,
      smartSpeed: 500,
      autoplay: !lowPowerMode,
      autoplayTimeout: 7000,
      navText: [
        '<span class="icon-left-arrow"></span>',
        '<span class="icon-right-arrow"></span>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        768: {
          items: 2,
        },
        992: {
          items: 3,
        },
        1200: {
          items: 4,
        },
        1320: {
          items: 4,
        },
      },
    });
  }



  //Testimonial One Carousel
  if ($(".testimonial-one__carousel").length) {
    $(".testimonial-one__carousel").owlCarousel({
      loop: true,
      margin: 30,
      nav: true,
      dots: true,
      smartSpeed: 500,
      autoplay: !lowPowerMode,
      autoplayTimeout: 7000,
      navText: [
        '<span class="icon-left-arrow"></span>',
        '<span class="icon-right-arrow"></span>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        768: {
          items: 2,
        },
        992: {
          items: 3,
        },
        1200: {
          items: 3,
        },
        1320: {
          items: 3,
        },
      },
    });
  }




  //Blog One Carousel
  if ($(".blog-one__carousel").length) {
    $(".blog-one__carousel").owlCarousel({
      loop: true,
      margin: 30,
      nav: true,
      dots: false,
      smartSpeed: 500,
      autoplay: !lowPowerMode,
      autoplayTimeout: 7000,
      navText: [
        '<span class="icon-left-arrow"></span>',
        '<span class="icon-right-arrow"></span>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        768: {
          items: 2,
        },
        992: {
          items: 3,
        },
        1200: {
          items: 3,
        },
        1320: {
          items: 3,
        },
      },
    });
  }




  //Team One Carousel
  if ($(".team-one__carousel").length) {
    $(".team-one__carousel").owlCarousel({
      loop: true,
      margin: 30,
      nav: true,
      dots: false,
      smartSpeed: 500,
      autoplay: !lowPowerMode,
      autoplayTimeout: 7000,
      navText: [
        '<span class="icon-left-arrow"></span>',
        '<span class="icon-right-arrow"></span>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        768: {
          items: 2,
        },
        992: {
          items: 3,
        },
        1200: {
          items: 4,
        },
        1320: {
          items: 4,
        },
      },
    });
  }





  //Testimonial Two Carousel
  if ($(".testimonial-two__carousel").length) {
    $(".testimonial-two__carousel").owlCarousel({
      loop: true,
      margin: 30,
      nav: true,
      dots: false,
      smartSpeed: 500,
      autoplay: !lowPowerMode,
      autoplayTimeout: 7000,
      navText: [
        '<span class="icon-left-arrow"></span>',
        '<span class="icon-right-arrow"></span>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        768: {
          items: 1,
        },
        992: {
          items: 1,
        },
        1200: {
          items: 1,
        },
        1320: {
          items: 1,
        },
      },
    });
  }






  //Portfolio Two Carousel
  if ($(".portfolio-two__carousel").length) {
    $(".portfolio-two__carousel").owlCarousel({
      loop: true,
      margin: 30,
      nav: false,
      dots: true,
      smartSpeed: 500,
      autoplay: !lowPowerMode,
      autoplayTimeout: 7000,
      navText: [
        '<span class="icon-left-arrow"></span>',
        '<span class="icon-right-arrow"></span>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        768: {
          items: 1,
        },
        992: {
          items: 1,
        },
        1200: {
          items: 1,
        },
        1320: {
          items: 1,
        },
      },
    });
  }





  //Blog Two Carousel
  if ($(".blog-two__carousel").length) {
    $(".blog-two__carousel").owlCarousel({
      loop: true,
      margin: 30,
      nav: false,
      dots: true,
      smartSpeed: 500,
      autoplay: !lowPowerMode,
      autoplayTimeout: 7000,
      navText: [
        '<span class="icon-left-arrow"></span>',
        '<span class="icon-right-arrow"></span>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        768: {
          items: 2,
        },
        992: {
          items: 3,
        },
        1200: {
          items: 3,
        },
        1320: {
          items: 3,
        },
      },
    });
  }







  //Blog Page Carousel
  if ($(".blog-carousel-style").length) {
    $(".blog-carousel-style").owlCarousel({
      loop: true,
      margin: 30,
      nav: false,
      dots: true,
      smartSpeed: 500,
      autoplay: !lowPowerMode,
      autoplayTimeout: 7000,
      navText: [
        '<span class="icon-right-arrow-1"></span>',
        '<span class="icon-right-arrow-1"></span>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        768: {
          items: 2,
        },
        992: {
          items: 3,
        },
        1200: {
          items: 3,
        },
        1320: {
          items: 3,
        },
      },
    });
  }



  //Team Page Carousel
  if ($(".team-carousel-style").length) {
    $(".team-carousel-style").owlCarousel({
      loop: true,
      margin: 30,
      nav: false,
      dots: true,
      smartSpeed: 500,
      autoplay: !lowPowerMode,
      autoplayTimeout: 7000,
      navText: [
        '<span class="icon-right-arrow-1"></span>',
        '<span class="icon-right-arrow-1"></span>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        768: {
          items: 2,
        },
        992: {
          items: 3,
        },
        1200: {
          items: 4,
        },
        1320: {
          items: 4,
        },
      },
    });
  }






  //Team Page Carousel
  if ($(".testimonial-carousel-style").length) {
    $(".testimonial-carousel-style").owlCarousel({
      loop: true,
      margin: 30,
      nav: false,
      dots: true,
      smartSpeed: 500,
      autoplay: !lowPowerMode,
      autoplayTimeout: 7000,
      navText: [
        '<span class="icon-right-arrow-1"></span>',
        '<span class="icon-right-arrow-1"></span>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        768: {
          items: 2,
        },
        992: {
          items: 3,
        },
        1200: {
          items: 3,
        },
        1320: {
          items: 3,
        },
      },
    });
  }








  // custom coursor
  if ($(".custom-cursor").length && !lowPowerMode) {

    var cursor = document.querySelector('.custom-cursor__cursor');
    var cursorinner = document.querySelector('.custom-cursor__cursor-two');
    var a = document.querySelectorAll('a');

    document.addEventListener('mousemove', function (e) {
      var x = e.clientX;
      var y = e.clientY;
      cursor.style.transform = `translate3d(calc(${e.clientX}px - 50%), calc(${e.clientY}px - 50%), 0)`
    });

    document.addEventListener('mousemove', function (e) {
      var x = e.clientX;
      var y = e.clientY;
      cursorinner.style.left = x + 'px';
      cursorinner.style.top = y + 'px';
    });

    document.addEventListener('mousedown', function () {
      cursor.classList.add('click');
      cursorinner.classList.add('custom-cursor__innerhover')
    });

    document.addEventListener('mouseup', function () {
      cursor.classList.remove('click')
      cursorinner.classList.remove('custom-cursor__innerhover')
    });

    a.forEach(item => {
      item.addEventListener('mouseover', () => {
        cursor.classList.add('custom-cursor__hover');
      });
      item.addEventListener('mouseleave', () => {
        cursor.classList.remove('custom-cursor__hover');
      });
    })
  }








  //Progress Count Bar
  if ($(".count-bar").length) {
    $(".count-bar").appear(
      function () {
        var el = $(this);
        var percent = el.data("percent");
        $(el).css("width", percent).addClass("counted");
      }, {
        accY: -50
      }
    );
  }








  //Progress Bar / Levels
  if ($(".progress-levels .progress-box .bar-fill").length) {
    $(".progress-box .bar-fill").each(
      function () {
        $(".progress-box .bar-fill").appear(function () {
          var progressWidth = $(this).attr("data-percent");
          $(this).css("width", progressWidth + "%");
        });
      }, {
        accY: 0
      }
    );
  }










  //Fact Counter + Text Count
  if ($(".count-box").length) {
    $(".count-box").appear(
      function () {
        var $t = $(this),
          n = $t.find(".count-text").attr("data-stop"),
          r = parseInt($t.find(".count-text").attr("data-speed"), 10);

        if (!$t.hasClass("counted")) {
          $t.addClass("counted");
          $({
            countNum: $t.find(".count-text").text()
          }).animate({
            countNum: n
          }, {
            duration: r,
            easing: "linear",
            step: function () {
              $t.find(".count-text").text(Math.floor(this.countNum));
            },
            complete: function () {
              $t.find(".count-text").text(this.countNum);
            }
          });
        }
      }, {
        accY: 0
      }
    );
  }


  //Fact Counter + Text Count
  if ($(".count-box-2").length) {
    $(".count-box-2").appear(
      function () {
        var $t = $(this),
          n = $t.find(".count-text").attr("data-stop"),
          r = parseInt($t.find(".count-text").attr("data-speed"), 10);

        if (!$t.hasClass("counted")) {
          $t.addClass("counted");
          $({
            countNum: $t.find(".count-text").text()
          }).animate({
            countNum: n
          }, {
            duration: r,
            easing: "linear",
            step: function () {
              $t.find(".count-text").text(Math.floor(this.countNum));
            },
            complete: function () {
              $t.find(".count-text").text(this.countNum);
            }
          });
        }
      }, {
        accY: 0
      }
    );
  }




  // Accrodion
  if ($(".accrodion-grp").length) {
    var accrodionGrp = $(".accrodion-grp");
    accrodionGrp.each(function () {
      var accrodionName = $(this).data("grp-name");
      var Self = $(this);
      var accordion = Self.find(".accrodion");
      Self.addClass(accrodionName);
      Self.find(".accrodion .accrodion-content").hide();
      Self.find(".accrodion.active").find(".accrodion-content").show();
      accordion.each(function () {
        $(this)
          .find(".accrodion-title")
          .on("click", function () {
            var parentItem = $(this).parent();
            var parentContent = parentItem.find(".accrodion-content");

            if (parentItem.hasClass("active")) {
              parentItem.removeClass("active");
              parentContent.stop(true, true).slideUp();
              return;
            }

            if (parentItem.hasClass("active") === false) {
              $(".accrodion-grp." + accrodionName)
                .find(".accrodion")
                .removeClass("active");
              $(".accrodion-grp." + accrodionName)
                .find(".accrodion")
                .find(".accrodion-content")
                .stop(true, true)
                .slideUp();
              parentItem.addClass("active");
              parentContent.stop(true, true).slideDown();
            }
          });
      });
    });
  }



  function toFloatOrNull(value) {
    var normalized = String(value || '').replace(/[^0-9.\-]/g, '');
    if (!normalized) return null;
    var parsed = parseFloat(normalized);
    return isNaN(parsed) ? null : parsed;
  }

  function readFormValue(form, name) {
    var el = form ? form.querySelector('[name="' + name + '"]') : null;
    return el ? String(el.value || '').trim() : '';
  }

  function cleanEventParams(params) {
    var cleaned = {};
    Object.keys(params || {}).forEach(function (key) {
      var value = params[key];
      if (value === null || value === undefined || value === '') return;
      cleaned[key] = value;
    });
    return cleaned;
  }

  function trackGa4Event(eventName, params) {
    if (!eventName) return;
    var payload = cleanEventParams(params || {});
    try {
      if (typeof window.gtag === 'function') {
        window.gtag('event', eventName, payload);
        return;
      }
      if (Array.isArray(window.dataLayer)) {
        window.dataLayer.push(Object.assign({ event: eventName }, payload));
      }
    } catch (e) {
      // Silent fail to avoid impacting form submission flow.
    }
  }

  function buildConversionContext(form) {
    var formType = readFormValue(form, 'form_type') || 'contact';
    var projectType = readFormValue(form, 'project_type') || readFormValue(form, 'subject');
    var budgetRange = readFormValue(form, 'budget_range');
    var quoteValue = toFloatOrNull(readFormValue(form, 'final_quote_preview'));
    if (quoteValue === null) {
      quoteValue = toFloatOrNull(readFormValue(form, 'selected_plan_price'));
    }
    var directOrder = readFormValue(form, 'start_order_payment') === '1';
    var currency = 'GBP';

    return {
      formType: formType,
      directOrder: directOrder,
      params: cleanEventParams({
        form_type: formType,
        project_type: projectType,
        budget_range: budgetRange,
        page_path: window.location.pathname,
        page_location: window.location.href,
        method: 'website_form',
        value: quoteValue,
        currency: quoteValue !== null ? currency : null
      })
    };
  }

  function submitAjaxForm(form) {
    $.ajax({
      url: $(form).attr("action"),
      type: "POST",
      data: $(form).serialize(),
      dataType: "json",
      headers: {
        "Accept": "application/json"
      },
      success: function (response) {
        var conversionContext = buildConversionContext(form);
        var hasCheckoutRedirect = !!(response && response.redirect_url && String(response.redirect_url).indexOf('checkout.stripe.com') !== -1);
        if (conversionContext.directOrder || hasCheckoutRedirect) {
          trackGa4Event('begin_checkout', conversionContext.params);
        } else if (conversionContext.formType === 'newsletter') {
          trackGa4Event('sign_up', conversionContext.params);
        } else if (conversionContext.formType === 'meeting') {
          trackGa4Event('schedule_meeting', conversionContext.params);
        } else {
          trackGa4Event('generate_lead', conversionContext.params);
        }

        var message = (response && response.message) ? response.message : "Request submitted successfully.";
        if ($(form).parent().find(".result").length) {
          $(form).parent().find(".result").html('<p class="contact-success-message">' + escapeHtml(message) + "</p>");
        }
        showSubmissionPopup("success", "Done", message);
        form.reset();
        if (response && response.redirect_url) {
          setTimeout(function () {
            window.location.href = response.redirect_url;
          }, 1200);
        }
      },
      error: function (xhr) {
        var message = "Message could not be sent right now. Please try again.";
        if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
          message = xhr.responseJSON.message;
        }
        if ($(form).parent().find(".result").length) {
          $(form).parent().find(".result").html('<p class="contact-error-message">' + escapeHtml(message) + "</p>");
        }
        showSubmissionPopup("error", "Not Sent", message);
      }
    });
  }

  if ($(".contact-form-validated").length) {
    $(".contact-form-validated").each(function () {
      let self = $(this);
      self.validate({
        // initialize the plugin
        rules: {
          name: {
            required: true
          },
          email: {
            required: true,
            email: true
          },
          message: {
            required: true
          }
        },
        submitHandler: function (form) {
          submitAjaxForm(form);
          return false;
        }
      });
    });
  }

  if ($(".newsletter-form-validated").length) {
    $(".newsletter-form-validated").each(function () {
      let self = $(this);
      self.validate({
        rules: {
          email: {
            required: true,
            email: true
          }
        },
        submitHandler: function (form) {
          submitAjaxForm(form);
          return false;
        }
      });
    });
  }

  function escapeHtml(value) {
    return String(value || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function showSubmissionPopup(type, title, message) {
    var popupId = "ars-submit-popup";
    var existing = document.getElementById(popupId);
    if (existing && existing.parentNode) {
      existing.parentNode.removeChild(existing);
    }

    if (!document.getElementById("ars-submit-popup-style")) {
      var style = document.createElement("style");
      style.id = "ars-submit-popup-style";
      style.textContent = "" +
        ".ars-submit-popup{position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px;background:rgba(9,22,54,.58);opacity:0;pointer-events:none;transition:opacity .22s ease;}" +
        ".ars-submit-popup.is-visible{opacity:1;pointer-events:auto;}" +
        ".ars-submit-popup__card{width:min(460px,100%);background:#fff;border-radius:18px;padding:24px 22px;box-shadow:0 24px 70px rgba(7,22,59,.35);transform:translateY(10px) scale(.98);transition:transform .24s ease;}" +
        ".ars-submit-popup.is-visible .ars-submit-popup__card{transform:translateY(0) scale(1);}" +
        ".ars-submit-popup__badge{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:700;margin-bottom:12px;color:#fff;}" +
        ".ars-submit-popup__badge--success{background:linear-gradient(135deg,#14c8a8,#1381e2);}" +
        ".ars-submit-popup__badge--error{background:linear-gradient(135deg,#f0647f,#e54761);}" +
        ".ars-submit-popup__title{margin:0 0 6px;font-size:24px;line-height:1.2;color:#0f2b5a;font-weight:800;}" +
        ".ars-submit-popup__text{margin:0;font-size:16px;line-height:1.6;color:#35517d;}" +
        ".ars-submit-popup__close{margin-top:16px;border:0;background:#1f7fd6;color:#fff;padding:11px 16px;border-radius:10px;font-weight:700;cursor:pointer;}";
      document.head.appendChild(style);
    }

    var popup = document.createElement("div");
    popup.className = "ars-submit-popup";
    popup.id = popupId;
    popup.innerHTML = '' +
      '<div class="ars-submit-popup__card" role="alertdialog" aria-live="assertive">' +
      '<div class="ars-submit-popup__badge ars-submit-popup__badge--' + (type === "success" ? "success" : "error") + '">' + (type === "success" ? "✓" : "!") + "</div>" +
      '<h4 class="ars-submit-popup__title">' + escapeHtml(title || (type === "success" ? "Success" : "Error")) + "</h4>" +
      '<p class="ars-submit-popup__text">' + escapeHtml(message || "") + "</p>" +
      '<button type="button" class="ars-submit-popup__close">OK</button>' +
      "</div>";

    document.body.appendChild(popup);
    requestAnimationFrame(function () {
      popup.classList.add("is-visible");
    });

    function closePopup() {
      popup.classList.remove("is-visible");
      setTimeout(function () {
        if (popup.parentNode) popup.parentNode.removeChild(popup);
      }, 220);
    }

    var closeBtn = popup.querySelector(".ars-submit-popup__close");
    if (closeBtn) {
      closeBtn.addEventListener("click", closePopup);
    }
    popup.addEventListener("click", function (event) {
      if (event.target === popup) {
        closePopup();
      }
    });

    if (type === "success") {
      setTimeout(closePopup, 2600);
    }
  }

  function initMeetingMultiStepForm(form) {
    if (!form) return;
    var resultBox = form.parentNode ? form.parentNode.querySelector('.result') : null;
    var stepNodes = Array.prototype.slice.call(form.querySelectorAll('[data-booking-step]'));
    var navNodes = Array.prototype.slice.call(form.querySelectorAll('[data-step-nav]'));
    var currentStep = 1;
    var totalSteps = stepNodes.length || 1;
    var requiredByStep = {
      1: ['meeting_date', 'meeting_slot'],
      2: ['name', 'email', 'phone', 'project_type']
    };

    function setResult(type, message) {
      if (!resultBox) return;
      if (!message) {
        resultBox.innerHTML = '';
        return;
      }
      var klass = type === 'success' ? 'contact-success-message' : 'contact-error-message';
      resultBox.innerHTML = '<p class="' + klass + '">' + escapeHtml(message) + '</p>';
    }

    function field(name) {
      return form.querySelector('[name="' + name + '"]');
    }

    function formattedDate(dateStr) {
      if (!dateStr || dateStr.indexOf('-') === -1) return dateStr;
      var parts = dateStr.split('-');
      if (parts.length !== 3) return dateStr;
      var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
      var year = parseInt(parts[0], 10);
      var month = parseInt(parts[1], 10) - 1;
      var day = parseInt(parts[2], 10);
      if (isNaN(year) || isNaN(month) || isNaN(day)) return dateStr;
      return day + ' ' + monthNames[month] + ' ' + year;
    }

    function updateReview() {
      var mapping = ['meeting_date', 'meeting_slot', 'name', 'email', 'phone', 'project_type', 'budget_range', 'message'];
      mapping.forEach(function (name) {
        var target = form.querySelector('[data-review="' + name + '"]');
        if (!target) return;
        var source = field(name);
        var value = source ? (source.value || '') : '';
        if (name === 'meeting_date') value = formattedDate(value);
        if (name === 'budget_range') value = value || 'Not specified';
        if (name === 'message') value = value || 'No extra details';
        target.textContent = value || '-';
      });
    }

    function showStep(stepNumber) {
      currentStep = Math.max(1, Math.min(stepNumber, totalSteps));
      stepNodes.forEach(function (node) {
        var step = parseInt(node.getAttribute('data-booking-step') || '1', 10);
        node.classList.toggle('is-active', step === currentStep);
      });
      navNodes.forEach(function (node) {
        var step = parseInt(node.getAttribute('data-step-nav') || '1', 10);
        node.classList.toggle('is-active', step === currentStep);
      });
      updateReview();
    }

    function validateStep(stepNumber) {
      var names = requiredByStep[stepNumber] || [];
      for (var i = 0; i < names.length; i++) {
        var name = names[i];
        var input = field(name);
        if (!input) continue;
        var value = String(input.value || '').trim();

        if (name === 'meeting_slot' && (input.disabled || !value)) {
          setResult('error', 'Please choose an available time slot.');
          input.focus();
          return false;
        }

        if (!value) {
          setResult('error', 'Please complete all required fields before continuing.');
          input.focus();
          return false;
        }

        if (name === 'email') {
          var okEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
          if (!okEmail) {
            setResult('error', 'Please enter a valid email address.');
            input.focus();
            return false;
          }
        }
      }

      setResult('', '');
      return true;
    }

    form.querySelectorAll('[data-step-next]').forEach(function (button) {
      button.addEventListener('click', function () {
        if (!validateStep(currentStep)) return;
        showStep(currentStep + 1);
      });
    });

    form.querySelectorAll('[data-step-prev]').forEach(function (button) {
      button.addEventListener('click', function () {
        showStep(currentStep - 1);
      });
    });

    navNodes.forEach(function (button) {
      button.addEventListener('click', function () {
        var targetStep = parseInt(button.getAttribute('data-step-nav') || '1', 10);
        if (targetStep <= currentStep) {
          showStep(targetStep);
          return;
        }
        if (targetStep > currentStep + 1) {
          targetStep = currentStep + 1;
        }
        if (!validateStep(currentStep)) return;
        showStep(targetStep);
      });
    });

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      if (!validateStep(1)) {
        showStep(1);
        return;
      }
      if (!validateStep(2)) {
        showStep(2);
        return;
      }

      updateReview();
      setResult('', '');
      var submitBtn = form.querySelector('button[type="submit"]');
      var submitLabel = submitBtn ? submitBtn.innerHTML : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="icon-right"></span> Booking...';
      }

      $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: $(form).serialize(),
        dataType: 'json',
        headers: {
          'Accept': 'application/json'
        },
        success: function (response) {
          var conversionParams = cleanEventParams({
            form_type: 'meeting',
            project_type: readFormValue(form, 'project_type'),
            budget_range: readFormValue(form, 'budget_range'),
            meeting_date: readFormValue(form, 'meeting_date'),
            meeting_slot: readFormValue(form, 'meeting_slot'),
            page_path: window.location.pathname,
            page_location: window.location.href,
            method: 'website_form'
          });
          trackGa4Event('schedule_meeting', conversionParams);

          if (response && response.redirect_url) {
            showSubmissionPopup('success', 'Booking Confirmed', (response && response.message) ? response.message : 'Meeting booked successfully.');
            setTimeout(function () {
              window.location.href = response.redirect_url;
            }, 1200);
            return;
          }
          setResult('success', (response && response.message) ? response.message : 'Meeting booked successfully.');
          showSubmissionPopup('success', 'Booking Confirmed', (response && response.message) ? response.message : 'Meeting booked successfully.');
          form.reset();
          showStep(1);
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitLabel;
          }
        },
        error: function (xhr) {
          var message = 'We could not submit the booking right now. Please try again.';
          if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
          }
          setResult('error', message);
          showSubmissionPopup('error', 'Booking Failed', message);
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitLabel;
          }
        }
      });
    });

    showStep(1);
  }

  if ($(".meeting-form-validated").length) {
    $(".meeting-form-validated").each(function () {
      var form = this;
      if (String(form.getAttribute('data-multistep')) === 'true') {
        initMeetingMultiStepForm(form);
        return;
      }

      let self = $(this);
      self.validate({
        rules: {
          name: {
            required: true
          },
          email: {
            required: true,
            email: true
          },
          phone: {
            required: true
          },
          meeting_date: {
            required: true
          },
          meeting_slot: {
            required: true
          },
          project_type: {
            required: true
          }
        },
        submitHandler: function (formElement) {
          var selectedDate = $(formElement).find('[name="meeting_date"]').val();
          var selectedSlot = $(formElement).find('[name="meeting_slot"] option:selected');
          if (!selectedDate || !selectedSlot.length || selectedSlot.prop('disabled')) {
            $(formElement).parent().find(".result").html('<p class="contact-error-message">Please choose an available date and time slot.</p>');
            return false;
          }
          submitAjaxForm(formElement);
          return false;
        }
      });
    });
  }

  (function () {
    var wrap = document.querySelector('.meeting-scheduler');
    if (!wrap) return;

    var monthLabel = wrap.querySelector('.meeting-calendar__month');
    var grid = wrap.querySelector('.meeting-calendar__grid');
    var prevBtn = wrap.querySelector('.meeting-calendar__prev');
    var nextBtn = wrap.querySelector('.meeting-calendar__next');
    var dateInput = wrap.querySelector('input[name="meeting_date"]');
    var slotInput = wrap.querySelector('select[name="meeting_slot"]');
    var projectInput = wrap.querySelector('select[name="project_type"]');
    var timezoneInput = wrap.querySelector('select[name="meeting_timezone"]');
    var googleLink = wrap.querySelector('.meeting-scheduler__calendar-link--google');
    var icsLink = wrap.querySelector('.meeting-scheduler__calendar-link--ics');
    var quickDateWrap = wrap.querySelector('[data-quick-dates]');
    var slotStatus = wrap.querySelector('[data-slot-status]');
    var timezoneLabel = wrap.querySelector('[data-timezone-label]');

    if (!monthLabel || !grid || !dateInput) return;

    var availabilityUrl = (wrap.dataset.availabilityUrl || '').trim();
    var fullyBookedDates = [];
    var bookedSlotsByDate = {};
    var availableSlotsByDate = {};
    var baseTimezone = 'Europe/London';

    var today = new Date();
    today.setHours(0, 0, 0, 0);
    var currentYear = today.getFullYear();
    var currentMonth = today.getMonth();

    function formatDate(y, m, d) {
      var mm = String(m + 1).padStart(2, '0');
      var dd = String(d).padStart(2, '0');
      return y + '-' + mm + '-' + dd;
    }

    function parseSlot(slotValue) {
      var parts = (slotValue || '').split(' - ');
      if (parts.length !== 2) return null;
      function to24(t) {
        var p = t.trim().split(' ');
        if (p.length !== 2) return null;
        var hm = p[0].split(':');
        if (hm.length !== 2) return null;
        var h = parseInt(hm[0], 10);
        var m = parseInt(hm[1], 10);
        var ampm = p[1].toUpperCase();
        if (ampm === 'PM' && h !== 12) h += 12;
        if (ampm === 'AM' && h === 12) h = 0;
        return { h: h, m: m };
      }
      return { start: to24(parts[0]), end: to24(parts[1]) };
    }

    function toGoogleDate(dt) {
      return dt.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
    }

    function dayLabel(dateStr) {
      if (!dateStr) return '';
      var dateObj = new Date(dateStr + 'T00:00:00');
      if (isNaN(dateObj.getTime())) return dateStr;
      var diff = Math.round((dateObj.getTime() - today.getTime()) / 86400000);
      if (diff === 0) return 'Today';
      if (diff === 1) return 'Tomorrow';
      return dateObj.toLocaleDateString('en-GB', { weekday: 'short', day: '2-digit', month: 'short' });
    }

    function applyDetectedTimezone() {
      if (!timezoneInput) return;
      var detected = '';
      try {
        detected = Intl.DateTimeFormat().resolvedOptions().timeZone || '';
      } catch (e) {
        detected = '';
      }
      if (!detected) return;

      var hasOption = false;
      Array.prototype.slice.call(timezoneInput.options).forEach(function (opt) {
        if (opt.value === detected) {
          hasOption = true;
        }
      });
      if (!hasOption) {
        var newOpt = document.createElement('option');
        newOpt.value = detected;
        newOpt.textContent = detected + ' (Detected)';
        timezoneInput.appendChild(newOpt);
      }
      timezoneInput.value = detected;
      updateTimezoneLabel();
    }

    function updateTimezoneLabel() {
      if (!timezoneLabel) return;
      timezoneLabel.textContent = timezoneInput && timezoneInput.value ? timezoneInput.value : baseTimezone;
    }

    function updateSlotStatus(dateStr) {
      if (!slotStatus) return;
      if (!dateStr) {
        slotStatus.textContent = 'Pick a date and we will auto-select the first available slot.';
        return;
      }

      var availableCount = -1;
      if (availableSlotsByDate[dateStr] && typeof availableSlotsByDate[dateStr].count === 'number') {
        availableCount = availableSlotsByDate[dateStr].count;
      }

      if (availableCount === 0) {
        slotStatus.textContent = 'No slots left on this date. Please pick another date.';
      } else if (availableCount > 0) {
        slotStatus.textContent = availableCount + ' slots available for ' + dayLabel(dateStr) + '. Earliest free slot selected automatically.';
      } else {
        slotStatus.textContent = 'Loading available slots for ' + dayLabel(dateStr) + '...';
      }
    }

    function renderQuickDates() {
      if (!quickDateWrap) return;
      quickDateWrap.innerHTML = '';

      var candidates = [];
      for (var i = 0; i < 21; i++) {
        var d = new Date(today);
        d.setDate(today.getDate() + i);
        var ds = formatDate(d.getFullYear(), d.getMonth(), d.getDate());
        if (fullyBookedDates.indexOf(ds) === -1) {
          candidates.push(ds);
        }
        if (candidates.length >= 4) break;
      }

      if (!candidates.length) {
        var empty = document.createElement('span');
        empty.className = 'meeting-scheduler__quick-empty';
        empty.textContent = 'All near dates are fully booked.';
        quickDateWrap.appendChild(empty);
        return;
      }

      candidates.forEach(function (dateStr) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'meeting-scheduler__quick-date' + (dateInput.value === dateStr ? ' is-active' : '');
        btn.setAttribute('data-date', dateStr);

        var label = document.createElement('span');
        label.textContent = dayLabel(dateStr);
        btn.appendChild(label);

        var meta = document.createElement('small');
        if (availableSlotsByDate[dateStr] && typeof availableSlotsByDate[dateStr].count === 'number') {
          meta.textContent = availableSlotsByDate[dateStr].count + ' slots';
        } else {
          meta.textContent = 'check';
        }
        btn.appendChild(meta);

        btn.addEventListener('click', function () {
          dateInput.value = dateStr;
          var s = dateStr.split('-');
          currentYear = parseInt(s[0], 10);
          currentMonth = parseInt(s[1], 10) - 1;
          renderCalendar(currentYear, currentMonth);
          updateSlotStatus(dateStr);
          fetchAvailability(dateStr).then(function () {
            updateCalendarLinks();
            renderQuickDates();
          });
        });

        quickDateWrap.appendChild(btn);
      });
    }

    function firstAvailableDate() {
      for (var i = 0; i < 45; i++) {
        var d = new Date(today);
        d.setDate(today.getDate() + i);
        var ds = formatDate(d.getFullYear(), d.getMonth(), d.getDate());
        if (fullyBookedDates.indexOf(ds) === -1) {
          return ds;
        }
      }
      return '';
    }

    function setSlotOptions(dateStr) {
      if (!slotInput) return;
      var currentValue = slotInput.value;
      var blockedSlots = bookedSlotsByDate[dateStr] || [];
      var hasAvailable = false;
      var placeholder = slotInput.options && slotInput.options.length ? slotInput.options[0] : null;

      Array.prototype.slice.call(slotInput.options).forEach(function (opt) {
        if (!opt.value) return;
        if (!opt.getAttribute('data-label')) {
          opt.setAttribute('data-label', opt.textContent);
        }
        var isBlocked = blockedSlots.indexOf(opt.value) !== -1;
        opt.disabled = isBlocked;
        opt.hidden = isBlocked;
        opt.textContent = opt.getAttribute('data-label');
        if (!isBlocked) hasAvailable = true;
      });

      if (!currentValue || blockedSlots.indexOf(currentValue) !== -1) {
        slotInput.value = '';
      }

      slotInput.disabled = !hasAvailable;
      if (placeholder && placeholder.value === '') {
        placeholder.textContent = hasAvailable ? 'Select Time Slot' : 'No slots available on this date';
      }

      if (!slotInput.value && hasAvailable) {
        var firstFree = Array.prototype.slice.call(slotInput.options).find(function (opt) {
          return opt.value && !opt.disabled;
        });
        if (firstFree) {
          slotInput.value = firstFree.value;
        }
      }

      updateSlotStatus(dateStr);
    }

    function fetchAvailability(dateStr) {
      if (!availabilityUrl) {
        setSlotOptions(dateStr || '');
        return Promise.resolve();
      }

      wrap.classList.add('is-loading-slots');
      var endpoint = availabilityUrl + (dateStr ? ('?date=' + encodeURIComponent(dateStr)) : '');
      return fetch(endpoint, { headers: { 'Accept': 'application/json' } })
        .then(function (res) { return res.json(); })
        .then(function (payload) {
          fullyBookedDates = Array.isArray(payload.fully_booked_dates) ? payload.fully_booked_dates : [];
          if (dateStr) {
            bookedSlotsByDate[dateStr] = Array.isArray(payload.booked_slots) ? payload.booked_slots : [];
            var availableList = Array.isArray(payload.available_slots) ? payload.available_slots : [];
            availableSlotsByDate[dateStr] = { count: availableList.length };
            setSlotOptions(dateStr);
          } else {
            setSlotOptions('');
          }
          renderQuickDates();
          renderCalendar(currentYear, currentMonth);
        })
        .catch(function () {
          setSlotOptions(dateStr || '');
          updateSlotStatus(dateStr || '');
          renderQuickDates();
        })
        .then(function () {
          wrap.classList.remove('is-loading-slots');
        });
    }

    function updateCalendarLinks() {
      if (!googleLink || !icsLink) return;
      var dateStr = dateInput.value;
      var slot = parseSlot(slotInput ? slotInput.value : '');
      if (!dateStr || !slot || !slot.start || !slot.end || (slotInput && slotInput.disabled)) {
        googleLink.setAttribute('href', '#');
        icsLink.setAttribute('href', '#');
        googleLink.setAttribute('aria-disabled', 'true');
        icsLink.setAttribute('aria-disabled', 'true');
        return;
      }
      googleLink.setAttribute('aria-disabled', 'false');
      icsLink.setAttribute('aria-disabled', 'false');

      var title = 'Meeting with ARSDeveloper';
      var selectedTz = timezoneInput && timezoneInput.value ? timezoneInput.value : baseTimezone;
      var details = 'Project Type: ' + (projectInput ? projectInput.value : 'General') + '\\nMeeting timezone: ' + selectedTz + '\\nUK schedule timezone: ' + baseTimezone + '\\nMeeting booked from website.';
      var start = new Date(dateStr + 'T00:00:00');
      start.setHours(slot.start.h, slot.start.m, 0, 0);
      var end = new Date(dateStr + 'T00:00:00');
      end.setHours(slot.end.h, slot.end.m, 0, 0);
      var location = 'Online meeting (UK timezone schedule)';

      var gUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
        + '&text=' + encodeURIComponent(title)
        + '&details=' + encodeURIComponent(details)
        + '&location=' + encodeURIComponent(location)
        + '&dates=' + encodeURIComponent(toGoogleDate(start) + '/' + toGoogleDate(end));
      googleLink.setAttribute('href', gUrl);

      icsLink.onclick = function (e) {
        e.preventDefault();
        var ics = [
          'BEGIN:VCALENDAR',
          'VERSION:2.0',
          'PRODID:-//ARSDeveloper//Meeting//EN',
          'BEGIN:VEVENT',
          'SUMMARY:' + title,
          'DESCRIPTION:' + details.replace(/\n/g, '\\n'),
          'LOCATION:' + location,
          'DTSTART:' + toGoogleDate(start).replace(/[-:]/g, ''),
          'DTEND:' + toGoogleDate(end).replace(/[-:]/g, ''),
          'END:VEVENT',
          'END:VCALENDAR'
        ].join('\r\n');
        var blob = new Blob([ics], { type: 'text/calendar;charset=utf-8' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'meeting-slot.ics';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(link.href);
      };
    }

    function renderCalendar(y, m) {
      grid.innerHTML = '';
      var first = new Date(y, m, 1);
      var firstDay = (first.getDay() + 6) % 7;
      var daysInMonth = new Date(y, m + 1, 0).getDate();
      var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
        'August', 'September', 'October', 'November', 'December'];
      monthLabel.textContent = monthNames[m] + ' ' + y;

      for (var i = 0; i < firstDay; i++) {
        var blank = document.createElement('button');
        blank.type = 'button';
        blank.className = 'meeting-calendar__day is-empty';
        blank.disabled = true;
        grid.appendChild(blank);
      }

      for (var d = 1; d <= daysInMonth; d++) {
        (function (day) {
          var btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'meeting-calendar__day';
          btn.textContent = day;
          var dateStr = formatDate(y, m, day);
          var dateObj = new Date(dateStr + 'T00:00:00');
          var isBooked = fullyBookedDates.indexOf(dateStr) !== -1;
          var isPast = dateObj < today;
          if (isBooked) btn.classList.add('is-booked');
          if (isPast) btn.classList.add('is-past');
          if (dateInput.value === dateStr) btn.classList.add('is-selected');
          if (isPast || isBooked) btn.disabled = true;

          btn.addEventListener('click', function () {
            if (isBooked || isPast) return;
            dateInput.value = dateStr;
            renderCalendar(currentYear, currentMonth);
            fetchAvailability(dateStr).then(updateCalendarLinks);
          });
          grid.appendChild(btn);
        })(d);
      }
    }

    prevBtn && prevBtn.addEventListener('click', function () {
      currentMonth -= 1;
      if (currentMonth < 0) {
        currentMonth = 11;
        currentYear -= 1;
      }
      renderCalendar(currentYear, currentMonth);
    });

    nextBtn && nextBtn.addEventListener('click', function () {
      currentMonth += 1;
      if (currentMonth > 11) {
        currentMonth = 0;
        currentYear += 1;
      }
      renderCalendar(currentYear, currentMonth);
    });

    dateInput.addEventListener('change', function () {
      var selected = dateInput.value;
      if (fullyBookedDates.indexOf(selected) !== -1) {
        dateInput.value = '';
        selected = '';
      }
      if (selected) {
        var s = selected.split('-');
        if (s.length === 3) {
          currentYear = parseInt(s[0], 10);
          currentMonth = parseInt(s[1], 10) - 1;
        }
      }
      renderCalendar(currentYear, currentMonth);
      updateSlotStatus(selected);
      fetchAvailability(selected).then(function () {
        updateCalendarLinks();
        renderQuickDates();
      });
    });

    slotInput && slotInput.addEventListener('change', updateCalendarLinks);
    projectInput && projectInput.addEventListener('change', updateCalendarLinks);
    timezoneInput && timezoneInput.addEventListener('change', function () {
      updateTimezoneLabel();
      updateCalendarLinks();
    });

    renderCalendar(currentYear, currentMonth);
    applyDetectedTimezone();
    updateTimezoneLabel();
    fetchAvailability('').then(function () {
      var chosen = dateInput.value || firstAvailableDate();
      if (chosen) {
        dateInput.value = chosen;
        var s = chosen.split('-');
        if (s.length === 3) {
          currentYear = parseInt(s[0], 10);
          currentMonth = parseInt(s[1], 10) - 1;
        }
        renderCalendar(currentYear, currentMonth);
        updateSlotStatus(chosen);
        return fetchAvailability(chosen).then(function () {
          updateCalendarLinks();
          renderQuickDates();
        });
      }
      updateSlotStatus('');
      updateCalendarLinks();
      renderQuickDates();
      return Promise.resolve();
    });
  })();

  // mailchimp form
  if ($(".mc-form").length) {
    $(".mc-form").each(function () {
      var Self = $(this);
      var mcURL = Self.data("url");
      var mcResp = Self.parent().find(".mc-form__response");

      Self.ajaxChimp({
        url: mcURL,
        callback: function (resp) {
          // appending response
          mcResp.append(function () {
            return '<p class="mc-message">' + resp.msg + "</p>";
          });
          // making things based on response
          if (resp.result === "success") {
            // Do stuff
            Self.removeClass("errored").addClass("successed");
            mcResp.removeClass("errored").addClass("successed");
            Self.find("input").val("");

            mcResp.find("p").fadeOut(10000);
          }
          if (resp.result === "error") {
            Self.removeClass("successed").addClass("errored");
            mcResp.removeClass("successed").addClass("errored");
            Self.find("input").val("");

            mcResp.find("p").fadeOut(10000);
          }
        }
      });
    });
  }

  if ($(".video-popup").length) {
    $(".video-popup").magnificPopup({
      type: "iframe",
      mainClass: "mfp-fade",
      removalDelay: 160,
      preloader: true,

      fixedContentPos: false
    });
  }

  if ($(".img-popup").length) {
    var groups = {};
    $(".img-popup").each(function () {
      var id = parseInt($(this).attr("data-group"), 10);

      if (!groups[id]) {
        groups[id] = [];
      }

      groups[id].push(this);
    });

    $.each(groups, function () {
      $(this).magnificPopup({
        type: "image",
        closeOnContentClick: true,
        closeBtnInside: false,
        gallery: {
          enabled: true
        }
      });
    });
  }





  //=== CountDownTimer===
  if ($('.coming-soon-countdown').length) {
    $('.coming-soon-countdown').each(function () {
      var Self = $(this);
      var countDate = Self.data('countdown-time'); // getting date

      Self.countdown(countDate, function (event) {
        $(this).html('<li> <div class="box"> <span class="days">' + event.strftime('%D') + '</span> <span class="timeRef">days</span> </div> </li> <li> <div class="box"> <span class="hours">' + event.strftime('%H') + '</span> <span class="timeRef clr-1">hrs</span> </div> </li> <li> <div class="box"> <span class="minutes">' + event.strftime('%M') + '</span> <span class="timeRef clr-2">mins</span> </div> </li> <li> <div class="box"> <span class="seconds">' + event.strftime('%S') + '</span> <span class="timeRef clr-3">secs</span> </div> </li>');
      });
    });
  };






  //Show Popup menu
  $(document).on("click", ".megamenu-clickable--toggler > a", function (e) {
    $("body").toggleClass("megamenu-popup-active");
    $(this).parent().find("ul").toggleClass("megamenu-clickable--active");
    e.preventDefault();
  });
  $(document).on("click", ".megamenu-clickable--close", function (e) {
    $("body").removeClass("megamenu-popup-active");
    $(".megamenu-clickable--active").removeClass("megamenu-clickable--active");
    e.preventDefault();
  });





  function dynamicCurrentMenuClass(selector) {
    let FileName = window.location.href.split("/").reverse()[0];

    selector.find("li").each(function () {
      let anchor = $(this).find("a");
      if ($(anchor).attr("href") == FileName) {
        $(this).addClass("current");
      }
    });
    // if any li has .current elmnt add class
    selector.children("li").each(function () {
      if ($(this).find(".current").length) {
        $(this).addClass("current");
      }
    });
    // if no file name return
    if ("" == FileName) {
      selector.find("li").eq(0).addClass("current");
    }
  }

  if ($(".main-menu__list").length) {
    // dynamic current class
    let mainNavUL = $(".main-menu__list");
    dynamicCurrentMenuClass(mainNavUL);
  }


  if ($(".main-menu__list").length && $(".mobile-nav__container").length) {
    let navContent = document.querySelector(".main-menu__list").outerHTML;
    let mobileNavContainer = document.querySelector(".mobile-nav__container");
    mobileNavContainer.innerHTML = navContent;
  }
  if ($(".sticky-header__content").length) {
    let navContent = document.querySelector(".main-menu").innerHTML;
    let mobileNavContainer = document.querySelector(".sticky-header__content");
    mobileNavContainer.innerHTML = navContent;
  }

  if ($(".mobile-nav__container .main-menu__list").length) {
    let dropdownAnchor = $(
      ".mobile-nav__container .main-menu__list .dropdown > a"
    );
    dropdownAnchor.each(function () {
      let self = $(this);
      let toggleBtn = document.createElement("BUTTON");
      toggleBtn.setAttribute("aria-label", "dropdown toggler");
      toggleBtn.innerHTML = "<i class='fa fa-angle-down'></i>";
      self.append(function () {
        return toggleBtn;
      });
      self.find("button").on("click", function (e) {
        e.preventDefault();
        let self = $(this);
        self.toggleClass("expanded");
        self.parent().toggleClass("expanded");
        self.parent().parent().children("ul").slideToggle();
      });
    });
  }

  if ($(".mobile-nav__toggler").length) {
    $(".mobile-nav__toggler").on("click", function (e) {
      e.preventDefault();
      $(".mobile-nav__wrapper").toggleClass("expanded");
      $("body").toggleClass("locked");
    });
  }




  //Header Search
  if ($('.searcher-toggler-box').length) {
    $('.searcher-toggler-box').on('click', function () {
      $('body').addClass('search-active');
    });
    $('.close-search').on('click', function () {
      $('body').removeClass('search-active');
    });

    $('.search-popup .color-layer').on('click', function () {
      $('body').removeClass('search-active');
    });
  }




  if ($(".wow").length && !lowPowerMode) {
    var wow = new WOW({
      boxClass: "wow", // animated element css class (default is wow)
      animateClass: "animated", // animation css class (default is animated)
      mobile: false, // keep lightweight behavior on smaller devices
      live: true // act on asynchronously loaded content (default is true)
    });
    wow.init();
  }






  if ($(".tabs-box").length) {
    $(".tabs-box .tab-buttons .tab-btn").on("click", function (e) {
      e.preventDefault();
      var target = $($(this).attr("data-tab"));

      if ($(target).is(":visible")) {
        return false;
      } else {
        target
          .parents(".tabs-box")
          .find(".tab-buttons")
          .find(".tab-btn")
          .removeClass("active-btn");
        $(this).addClass("active-btn");
        target
          .parents(".tabs-box")
          .find(".tabs-content")
          .find(".tab")
          .fadeOut(0);
        target
          .parents(".tabs-box")
          .find(".tabs-content")
          .find(".tab")
          .removeClass("active-tab");
        $(target).fadeIn(300);
        $(target).addClass("active-tab");
      }
    });
  }












  // ===Portfolio===
  function projectMasonaryLayout() {
    if ($(".masonary-layout").length) {
      $(".masonary-layout").isotope({
        layoutMode: "masonry"
      });
    }
    if ($(".post-filter").length) {
      $(".post-filter li")
        .children(".filter-text")
        .on("click", function () {
          var Self = $(this);
          var selector = Self.parent().attr("data-filter");
          $(".post-filter li").removeClass("active");
          Self.parent().addClass("active");
          $(".filter-layout").isotope({
            filter: selector,
            animationOptions: {
              duration: 500,
              easing: "linear",
              queue: false
            }
          });
          return false;
        });
    }

    if ($(".post-filter.has-dynamic-filters-counter").length) {
      // var allItem = $('.single-filter-item').length;
      var activeFilterItem = $(".post-filter.has-dynamic-filters-counter").find(
        "li"
      );
      activeFilterItem.each(function () {
        var filterElement = $(this).data("filter");
        var count = $(".filter-layout").find(filterElement).length;
        $(this)
          .children(".filter-text")
          .append('<span class="count">' + count + "</span>");
      });
    }
  }













  function SmoothMenuScroll() {
    var anchor = $(".scrollToLink");
    if (anchor.length) {
      anchor.children("a").bind("click", function (event) {
        if ($(window).scrollTop() > 10) {
          var headerH = "90";
        } else {
          var headerH = "90";
        }
        var target = $(this);
        $("html, body")
          .stop()
          .animate({
              scrollTop: $(target.attr("href")).offset().top - headerH + "px"
            },
            1200,
            "easeInOutExpo"
          );
        anchor.removeClass("current");
        anchor.removeClass("current-menu-ancestor");
        anchor.removeClass("current_page_item");
        anchor.removeClass("current-menu-parent");
        target.parent().addClass("current");
        event.preventDefault();
      });
    }
  }
  SmoothMenuScroll();

  function OnePageMenuScroll() {
    var windscroll = $(window).scrollTop();
    if (windscroll >= 117) {
      var menuAnchor = $(".one-page-scroll-menu .scrollToLink").children("a");
      menuAnchor.each(function () {
        var sections = $(this).attr("href");
        $(sections).each(function () {
          if ($(this).offset().top <= windscroll + 100) {
            var Sectionid = $(sections).attr("id");
            $(".one-page-scroll-menu").find("li").removeClass("current");
            $(".one-page-scroll-menu").find("li").removeClass("current-menu-ancestor");
            $(".one-page-scroll-menu").find("li").removeClass("current_page_item");
            $(".one-page-scroll-menu").find("li").removeClass("current-menu-parent");
            $(".one-page-scroll-menu")
              .find("a[href*=\\#" + Sectionid + "]")
              .parent()
              .addClass("current");
          }
        });
      });
    } else {
      $(".one-page-scroll-menu li.current").removeClass("current");
      $(".one-page-scroll-menu li:first").addClass("current");
    }
  }






  /*-- Handle Scrollbar --*/
  function handleScrollbar() {
    const bodyHeight = $("body").height();
    const scrollPos = $(window).innerHeight() + $(window).scrollTop();
    let percentage = (scrollPos / bodyHeight) * 100;
    if (percentage > 100) {
      percentage = 100;
    }
    $(".scroll-to-top .scroll-to-top__inner").css("width", percentage + "%");
  }




  // Animation gsap 
  function title_animation() {
    var tg_var = jQuery('.sec-title-animation');
    if (!tg_var.length) {
      return;
    }
    const quotes = document.querySelectorAll(".sec-title-animation .title-animation");

    quotes.forEach(quote => {

      //Reset if needed
      if (quote.animation) {
        quote.animation.progress(1).kill();
        quote.split.revert();
      }

      var getclass = quote.closest('.sec-title-animation').className;
      var animation = getclass.split('animation-');
      if (animation[1] == "style4") return

      quote.split = new SplitText(quote, {
        type: "lines,words,chars",
        linesClass: "split-line"
      });
      gsap.set(quote, {
        perspective: 400
      });

      if (animation[1] == "style1") {
        gsap.set(quote.split.chars, {
          opacity: 0,
          y: "90%",
          rotateX: "-40deg"
        });
      }
      if (animation[1] == "style2") {
        gsap.set(quote.split.chars, {
          opacity: 0,
          x: "50"
        });
      }
      if (animation[1] == "style3") {
        gsap.set(quote.split.chars, {
          opacity: 0,
        });
      }
      quote.animation = gsap.to(quote.split.chars, {
        scrollTrigger: {
          trigger: quote,
          start: "top 90%",
        },
        x: "0",
        y: "0",
        rotateX: "0",
        opacity: 1,
        duration: 1,
        ease: Back.easeOut,
        stagger: .02
      });
    });
  }
  if (typeof ScrollTrigger !== "undefined" && typeof SplitText !== "undefined" && typeof gsap !== "undefined" && !lowPowerMode) {
    ScrollTrigger.addEventListener("refresh", title_animation);
  }







  // window load event
  $(window).on("load", function () {


    projectMasonaryLayout();
    fullHeight();
    if (!lowPowerMode) {
      title_animation();
    }
    priceFilter();








    if ($(".post-filter").length) {
      var postFilterList = $(".post-filter li");
      // for first init
      $(".filter-layout").isotope({
        filter: ".filter-item",
        animationOptions: {
          duration: 500,
          easing: "linear",
          queue: false
        }
      });
      // on click filter links
      postFilterList.on("click", function () {
        var Self = $(this);
        var selector = Self.attr("data-filter");
        postFilterList.removeClass("active");
        Self.addClass("active");

        $(".filter-layout").isotope({
          filter: selector,
          animationOptions: {
            duration: 500,
            easing: "linear",
            queue: false
          }
        });
        return false;
      });
    }

    if ($(".post-filter.has-dynamic-filter-counter").length) {
      // var allItem = $('.single-filter-item').length;

      var activeFilterItem = $(".post-filter.has-dynamic-filter-counter").find(
        "li"
      );

      activeFilterItem.each(function () {
        var filterElement = $(this).data("filter");
        var count = $(".filter-layout").find(filterElement).length;
        $(this).append("<sup>[" + count + "]</sup>");
      });
    }





    if ($(".marquee_mode").length && !lowPowerMode && typeof $.fn.marquee === "function") {
      $('.marquee_mode').marquee({
        speed: 40,
        gap: 0,
        delayBeforeStart: 0,
        direction: 'left',
        duplicated: true,
        pauseOnHover: true,
        startVisible: true,
      });
    }




    // Curved Circle
    if ($(".why-choose-one__curved-circle").length && !lowPowerMode) {
      $(".why-choose-one__curved-circle").circleType({
        position: "absolute",
        dir: 1,
        radius: 85,
        forceHeight: true,
        forceWidth: true,
      });
    }




  });











  // window scroll event

  $(window).on("scroll", function () {
    if ($(".stricked-menu").length) {
      var headerScrollPos = 130;
      var stricky = $(".stricked-menu");
      if ($(window).scrollTop() > headerScrollPos) {
        stricky.addClass("stricky-fixed");
      } else if ($(this).scrollTop() <= headerScrollPos) {
        stricky.removeClass("stricky-fixed");
      }
    }

    OnePageMenuScroll();

  });

  $(window).on("scroll", function () {
    handleScrollbar();
    if ($(".sticky-header--one-page").length) {
      var headerScrollPos = 130;
      var stricky = $(".sticky-header--one-page");
      if ($(window).scrollTop() > headerScrollPos) {
        stricky.addClass("active");
      } else if ($(this).scrollTop() <= headerScrollPos) {
        stricky.removeClass("active");
      }
    }

    var scrollToTopBtn = ".scroll-to-top";
    if (scrollToTopBtn.length) {
      if ($(window).scrollTop() > 500) {
        $(scrollToTopBtn).addClass("show");
      } else {
        $(scrollToTopBtn).removeClass("show");
      }
    }
  });











  $('select:not(.ignore)').niceSelect();

  // Lead forms tabs
  if ($(".lead-forms-tabs__btn").length && $(".lead-forms-section").length) {
    var leadTabsNav = $(".lead-forms-tabs__nav");
    if (leadTabsNav.length) {
      leadTabsNav
        .attr("role", "tablist")
        .attr("aria-label", "Lead Forms")
        .attr("aria-orientation", "horizontal");
    }

    $(".lead-forms-tabs__btn").each(function () {
      var tabKey = ($(this).data("lead-tab") || "").toString();
      var fallbackId = "lead-tab-" + (tabKey || "audit");
      var targetPanelId = tabKey === "estimate" ? "estimate-section" : "free-audit-section";

      if (!$(this).attr("id")) {
        $(this).attr("id", fallbackId);
      }

      $(this)
        .attr("role", "tab")
        .attr("aria-controls", targetPanelId)
        .attr("tabindex", "-1");
    });

    $(".lead-forms-section").each(function () {
      var panelKey = ($(this).data("lead-panel") || "").toString();
      var labelledBy = panelKey === "estimate" ? "lead-tab-estimate" : "lead-tab-audit";
      $(this)
        .attr("role", "tabpanel")
        .attr("aria-labelledby", labelledBy)
        .attr("aria-hidden", "true")
        .attr("tabindex", "-1");
    });

    var setLeadTab = function (tabKey) {
      var safeTabKey = (tabKey || "audit").toString();
      $(".lead-forms-tabs__btn")
        .removeClass("is-active")
        .attr("aria-selected", "false")
        .attr("tabindex", "-1");

      $('.lead-forms-tabs__btn[data-lead-tab="' + safeTabKey + '"]')
        .addClass("is-active")
        .attr("aria-selected", "true")
        .attr("tabindex", "0");

      $(".lead-forms-section")
        .removeClass("is-active")
        .attr("aria-hidden", "true")
        .attr("tabindex", "-1")
        .attr("hidden", "hidden");

      $('.lead-forms-section[data-lead-panel="' + safeTabKey + '"]')
        .addClass("is-active")
        .attr("aria-hidden", "false")
        .attr("tabindex", "0")
        .removeAttr("hidden");

      if ($.fn.niceSelect) {
        try {
          $('select:not(.ignore)').niceSelect('update');
        } catch (e) {
          // no-op
        }
      }
    };

    $(".lead-forms-tabs__btn").on("click", function () {
      setLeadTab($(this).data("lead-tab"));
    });

    $(".lead-forms-tabs__btn").on("keydown", function (event) {
      var key = event.key || event.which;
      var tabs = $(".lead-forms-tabs__btn");
      var currentIndex = tabs.index(this);
      var targetIndex = currentIndex;

      if (key === "ArrowRight" || key === 39) {
        targetIndex = (currentIndex + 1) % tabs.length;
      } else if (key === "ArrowLeft" || key === 37) {
        targetIndex = (currentIndex - 1 + tabs.length) % tabs.length;
      } else if (key === "Home" || key === 36) {
        targetIndex = 0;
      } else if (key === "End" || key === 35) {
        targetIndex = tabs.length - 1;
      } else if (key === "Enter" || key === " " || key === 13 || key === 32) {
        event.preventDefault();
        setLeadTab($(this).data("lead-tab"));
        return;
      } else {
        return;
      }

      event.preventDefault();
      var targetTab = tabs.eq(targetIndex);
      targetTab.trigger("focus");
      setLeadTab(targetTab.data("lead-tab"));
    });

    $(document).on("click", 'a[href="#free-audit"], a[href="#free-audit-section"]', function () {
      setLeadTab("audit");
    });

    $(document).on("click", 'a[href="#estimate-section"]', function () {
      setLeadTab("estimate");
    });

    var currentHash = (window.location.hash || "").toLowerCase();
    if (currentHash.indexOf("estimate") !== -1) {
      setLeadTab("estimate");
    } else {
      setLeadTab("audit");
    }
  }

  // Instant estimator preview
  (function () {
    var section = document.getElementById('estimate-section');
    if (!section) return;

    var form = section.querySelector('form');
    if (!form) return;

    var projectType = form.querySelector('select[name="project_type"]');
    var budgetRange = form.querySelector('select[name="budget_range"]');
    var budgetNode = section.querySelector('[data-estimate-budget]');
    var timelineNode = section.querySelector('[data-estimate-timeline]');
    var priorityNode = section.querySelector('[data-estimate-priority]');

    if (!projectType || !budgetRange || !budgetNode || !timelineNode || !priorityNode) return;

    function estimateModel(type, budget) {
      var t = (type || '').toLowerCase();
      var b = (budget || '').toLowerCase();

      var budgetText = budget || 'GBP 2k - 5k';
      var timeline = '2 - 4 weeks';
      var priority = 'Balanced speed and quality';

      if (t.indexOf('ecommerce') !== -1) {
        timeline = '4 - 8 weeks';
        priority = 'Conversion + checkout reliability';
      } else if (t.indexOf('crm') !== -1 || t.indexOf('portal') !== -1) {
        timeline = '6 - 12 weeks';
        priority = 'Workflow automation and scalability';
      } else if (t.indexOf('seo') !== -1) {
        timeline = '8 - 12 weeks (initial gains)';
        priority = 'Technical fixes + ranking growth';
      }

      if (b.indexOf('10k') !== -1 || b.indexOf('20k') !== -1) {
        priority = 'Growth-focused execution with advanced scope';
      } else if (b.indexOf('2k - 5k') !== -1 || b.indexOf('below') !== -1) {
        priority = 'MVP-first delivery with core features';
      }

      return {
        budget: budgetText,
        timeline: timeline,
        priority: priority
      };
    }

    function renderEstimate() {
      var result = estimateModel(projectType.value, budgetRange.value);
      budgetNode.textContent = result.budget;
      timelineNode.textContent = result.timeline;
      priorityNode.textContent = result.priority;
    }

    projectType.addEventListener('change', renderEstimate);
    budgetRange.addEventListener('change', renderEstimate);
    renderEstimate();
  })();

  // Pricing package selector + next-step links
  (function () {
    var planButtons = Array.prototype.slice.call(document.querySelectorAll('.js-plan-select'));
    var selectedPlanNode = document.querySelector('[data-selected-plan]');
    var startLinks = Array.prototype.slice.call(document.querySelectorAll('[data-start-link][data-base-href]'));

    if (!planButtons.length || !selectedPlanNode || !startLinks.length) return;

    function billingLabel(value) {
      if (value === 'one_time') return 'one-time';
      if (value === 'subscription') return 'subscription';
      return (value || '').replace(/_/g, ' ').trim();
    }

    function buildLink(baseHref, plan, billing) {
      if (!plan) return baseHref;

      var source = baseHref || '';
      var hash = '';
      var hashIndex = source.indexOf('#');
      if (hashIndex !== -1) {
        hash = source.substring(hashIndex);
        source = source.substring(0, hashIndex);
      }

      var joiner = source.indexOf('?') === -1 ? '?' : '&';
      var url = source + joiner + 'plan=' + encodeURIComponent(plan);
      if (billing) {
        url += '&billing=' + encodeURIComponent(billing);
      }

      return url + hash;
    }

    function selectPlan(plan, billing, activeButton) {
      var safePlan = (plan || '').trim();
      var safeBilling = (billing || '').trim();
      var suffix = billingLabel(safeBilling);

      selectedPlanNode.textContent = safePlan !== '' ? (suffix ? (safePlan + ' (' + suffix + ')') : safePlan) : 'Not selected yet';

      startLinks.forEach(function (link) {
        var baseHref = link.getAttribute('data-base-href') || link.getAttribute('href') || '';
        link.setAttribute('href', buildLink(baseHref, safePlan, safeBilling));
      });

      planButtons.forEach(function (button) {
        button.classList.remove('is-selected');
        var card = button.closest('.pricing-one__single, .pricing-one__single-last');
        if (card) card.classList.remove('is-selected-plan');
      });

      if (activeButton) {
        activeButton.classList.add('is-selected');
        var activeCard = activeButton.closest('.pricing-one__single, .pricing-one__single-last');
        if (activeCard) activeCard.classList.add('is-selected-plan');
      }
    }

    planButtons.forEach(function (button) {
      button.addEventListener('click', function (event) {
        var targetId = button.getAttribute('href') || '';
        var plan = button.getAttribute('data-plan') || '';
        var billing = button.getAttribute('data-billing') || '';

        selectPlan(plan, billing, button);

        if (targetId && targetId.charAt(0) === '#') {
          var target = document.querySelector(targetId);
          if (target) {
            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        }
      });
    });

    var params = new URLSearchParams(window.location.search);
    var queryPlan = (params.get('plan') || '').trim();
    if (queryPlan !== '') {
      selectPlan(queryPlan, (params.get('billing') || '').trim(), null);
    }
  })();

  // FAQ intent switch
  (function () {
    var chips = document.querySelectorAll('.faq-intent__chip');
    var panels = document.querySelectorAll('.faq-one__right [data-faq-panel]');
    if (!chips.length || !panels.length) return;

    function openPanel(key) {
      chips.forEach(function (chip) {
        chip.classList.toggle('is-active', chip.getAttribute('data-faq-intent') === key);
      });

      panels.forEach(function (panel) {
        var isActive = panel.getAttribute('data-faq-panel') === key;
        panel.classList.toggle('is-active', isActive);
        panel.style.display = isActive ? 'block' : 'none';
      });
    }

    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        openPanel(chip.getAttribute('data-faq-intent'));
      });
    });

    openPanel('website');
  })();



})(jQuery);
