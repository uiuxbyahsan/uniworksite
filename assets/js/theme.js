/* Unitourk theme interactions */
(function ($) {
  "use strict";
  var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  document.addEventListener("DOMContentLoaded", function () {

    /* ---- sticky header + breadcrumb offset ---- */
    var header = document.getElementById("header");
    if (header) {
      var onScroll = function () { header.classList.toggle("scrolled", window.scrollY > 8); };
      window.addEventListener("scroll", onScroll, { passive: true });
      onScroll();
      // keep the sticky breadcrumb pinned exactly beneath the navbar at any breakpoint
      var setHeaderH = function () {
        document.documentElement.style.setProperty("--header-h", header.offsetHeight + "px");
      };
      setHeaderH();
      window.addEventListener("load", setHeaderH);
      window.addEventListener("resize", setHeaderH);
      if (window.ResizeObserver) { new ResizeObserver(setHeaderH).observe(header); }
    }

    /* ---- hero carousel (dots + autoplay) ---- */
    var track = document.getElementById("heroTrack");
    if (track) {
      var slides = Array.prototype.slice.call(track.querySelectorAll("[data-slide]"));
      var dotsWrap = document.getElementById("heroDots");
      var idx = 0, timer = null, DELAY = 6000;
      slides.forEach(function (_, i) {
        var b = document.createElement("button");
        b.setAttribute("role", "tab");
        b.setAttribute("aria-label", "Go to slide " + (i + 1));
        b.addEventListener("click", function () { go(i); restart(); });
        dotsWrap.appendChild(b);
      });
      var dots = Array.prototype.slice.call(dotsWrap.children);
      function go(i) {
        idx = (i + slides.length) % slides.length;
        slides.forEach(function (s, n) { s.classList.toggle("active", n === idx); });
        dots.forEach(function (d, n) { d.classList.toggle("active", n === idx); });
      }
      function restart() { if (timer) clearInterval(timer); if (!reduce && slides.length > 1) timer = setInterval(function () { go(idx + 1); }, DELAY); }
      var hero = document.querySelector(".hero");
      hero.addEventListener("mouseenter", function () { if (timer) clearInterval(timer); });
      hero.addEventListener("mouseleave", restart);
      var sx = 0;
      track.addEventListener("touchstart", function (e) { sx = e.touches[0].clientX; }, { passive: true });
      track.addEventListener("touchend", function (e) { var dx = e.changedTouches[0].clientX - sx; if (Math.abs(dx) > 45) { go(idx + (dx < 0 ? 1 : -1)); restart(); } }, { passive: true });
      go(0); restart();
    }

    /* ---- reveal on scroll (with failsafe) ---- */
    var reveals = Array.prototype.slice.call(document.querySelectorAll(".reveal"));
    var revealEl = function (el) {
      if (el.classList.contains("in")) return;
      var sibs = Array.prototype.slice.call(el.parentElement.children).filter(function (c) { return c.classList.contains("reveal"); });
      el.style.transitionDelay = Math.min(sibs.indexOf(el), 5) * 70 + "ms";
      el.classList.add("in");
    };
    if ("IntersectionObserver" in window && !reduce) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) { if (en.isIntersecting) { revealEl(en.target); io.unobserve(en.target); } });
      }, { threshold: 0.12, rootMargin: "0px 0px -8% 0px" });
      reveals.forEach(function (r) { io.observe(r); });
      var sweep = function () { reveals.forEach(function (r) { var rect = r.getBoundingClientRect(); if (rect.top < innerHeight * 0.94 && rect.bottom > 0) revealEl(r); }); };
      window.addEventListener("load", sweep); setTimeout(sweep, 600); setTimeout(function () { reveals.forEach(revealEl); }, 2600);
    } else { reveals.forEach(function (r) { r.classList.add("in"); }); }

    /* ---- mobile nav ---- */
    var mobileNav = document.getElementById("mobileNav");
    var open = function () { mobileNav.classList.add("open"); document.body.style.overflow = "hidden"; };
    var close = function () { mobileNav.classList.remove("open"); document.body.style.overflow = ""; };
    var openBtn = document.getElementById("openMenu"), closeBtn = document.getElementById("closeMenu");
    if (openBtn) openBtn.addEventListener("click", open);
    if (closeBtn) closeBtn.addEventListener("click", close);
    if (mobileNav) {
      mobileNav.addEventListener("click", function (e) { if (e.target === mobileNav) close(); });
      mobileNav.querySelectorAll("[data-close]").forEach(function (a) { a.addEventListener("click", close); });
    }

    /* ---- search toggle ---- */
    var st = document.getElementById("utSearchToggle"), sf = document.querySelector(".ut-search");
    if (st && sf) st.addEventListener("click", function () { sf.classList.toggle("open"); var i = sf.querySelector("input[type=search]"); if (sf.classList.contains("open") && i) i.focus(); });

    /* ---- testimonials slider (dots + autoplay) ---- */
    var tTrack = document.getElementById("tstTrack");
    if (tTrack) {
      var tcards = Array.prototype.slice.call(tTrack.children);
      var tDots = document.getElementById("tstDots");
      var GAP = 22, ti = 0, tTimer = null;
      var perView = function () { return innerWidth <= 680 ? 1 : innerWidth <= 1080 ? 2 : 3; };
      var maxIdx = function () { return Math.max(0, tcards.length - perView()); };
      var tRender = function () {
        var step = tcards[0].getBoundingClientRect().width + GAP;
        tTrack.style.transform = "translateX(" + (-ti * step) + "px)";
        Array.prototype.slice.call(tDots.children).forEach(function (d, n) { d.classList.toggle("active", n === ti); });
      };
      var tGo = function (i) { var m = maxIdx(); ti = i > m ? 0 : (i < 0 ? m : i); tRender(); };
      var tRestart = function () { if (tTimer) clearInterval(tTimer); if (!reduce && maxIdx() > 0) tTimer = setInterval(function () { tGo(ti + 1); }, 5000); };
      var buildTDots = function () {
        tDots.innerHTML = "";
        for (var i = 0; i <= maxIdx(); i++) {
          (function (i) { var b = document.createElement("button"); b.setAttribute("aria-label", "Testimonial group " + (i + 1)); b.addEventListener("click", function () { tGo(i); tRestart(); }); tDots.appendChild(b); })(i);
        }
      };
      var tsx = 0;
      tTrack.addEventListener("touchstart", function (e) { tsx = e.touches[0].clientX; }, { passive: true });
      tTrack.addEventListener("touchend", function (e) { var dx = e.changedTouches[0].clientX - tsx; if (Math.abs(dx) > 45) { tGo(ti + (dx < 0 ? 1 : -1)); tRestart(); } }, { passive: true });
      var slider = document.querySelector(".tst-slider");
      slider.addEventListener("mouseenter", function () { if (tTimer) clearInterval(tTimer); });
      slider.addEventListener("mouseleave", tRestart);
      var trt; window.addEventListener("resize", function () { clearTimeout(trt); trt = setTimeout(function () { ti = Math.min(ti, maxIdx()); buildTDots(); tRender(); }, 150); });
      buildTDots(); tRender(); tRestart();
    }

    /* ================= INQUIRY DRAWER ================= */
    var drawer = document.getElementById("utDrawer");
    var openDrawer = function () { if (!drawer) return; drawer.classList.add("open"); document.body.style.overflow = "hidden"; };
    var closeDrawer = function () { if (!drawer) return; drawer.classList.remove("open"); document.body.style.overflow = ""; };

    document.querySelectorAll(".ut-drawer-open").forEach(function (b) {
      b.addEventListener("click", function (e) { e.preventDefault(); openDrawer(); });
    });
    if (drawer) {
      drawer.addEventListener("click", function (e) { if (e.target === drawer) closeDrawer(); });
      var dc = drawer.querySelector(".ut-drawer-close");
      if (dc) dc.addEventListener("click", closeDrawer);
    }
    document.addEventListener("keydown", function (e) { if (e.key === "Escape") { closeDrawer(); close(); } });

    /* open drawer whenever Woo finishes an AJAX add-to-cart (loop cards) */
    $(document.body).on("added_to_cart", function () { openDrawer(); });

    /* single product "Add to Inquiry" -> ajax add, refresh fragments, open drawer */
    $(document).on("submit", ".ut-add-form", function (e) {
      e.preventDefault();
      var $form = $(this);
      var id = $form.data("product-id");
      var qty = parseInt($form.find("input[name=quantity]").val(), 10) || 1;
      var $btn = $form.find(".ut-add-inquiry").addClass("loading");
      var url = (UNITOURK.wcAjax || "").replace("%%endpoint%%", "add_to_cart");
      $.post(url, { product_id: id, quantity: qty }, function (resp) {
        $btn.removeClass("loading");
        applyFragments(resp);
        openDrawer();
      }).fail(function () { $btn.removeClass("loading"); });
    });

    /* single product quantity stepper */
    $(document).on("click", ".ut-qty-input .ut-plus, .ut-qty-input .ut-minus", function () {
      var $i = $(this).parent().find("input");
      var v = parseInt($i.val(), 10) || 1;
      v = $(this).hasClass("ut-plus") ? v + 1 : Math.max(1, v - 1);
      $i.val(v);
    });

    /* drawer line quantity / remove */
    function applyFragments(resp) {
      if (resp && resp.fragments) {
        $.each(resp.fragments, function (k, v) { $(k).replaceWith(v); });
      } else {
        $(document.body).trigger("wc_fragment_refresh");
      }
    }
    function updateLine(key, qty) {
      $.post(UNITOURK.ajaxUrl, { action: "unitourk_update_cart", nonce: UNITOURK.nonce, key: key, qty: qty }, applyFragments);
    }
    $(document).on("click", ".ut-line .ut-qplus, .ut-line .ut-qminus, .ut-line .ut-remove", function () {
      var $line = $(this).closest(".ut-line");
      var key = $line.data("key");
      var cur = parseInt($line.find(".ut-qnum").text(), 10) || 1;
      if ($(this).hasClass("ut-remove")) { updateLine(key, 0); }
      else if ($(this).hasClass("ut-qplus")) { updateLine(key, cur + 1); }
      else { updateLine(key, Math.max(1, cur - 1)); }
    });

    /* contact form submit (standalone Contact page) */
    $(document).on("submit", ".ut-contact-form", function (e) {
      e.preventDefault();
      var $f = $(this), $msg = $f.find(".ut-form-msg"), $btn = $f.find(".ut-contact-submit");
      var data = {
        action: "unitourk_contact", nonce: UNITOURK.nonce,
        name: $f.find("[name=name]").val(), company: $f.find("[name=company]").val(),
        email: $f.find("[name=email]").val(), phone: $f.find("[name=phone]").val(),
        message: $f.find("[name=message]").val()
      };
      $btn.addClass("loading"); $msg.removeClass("show ok err");
      $.post(UNITOURK.ajaxUrl, data, function (resp) {
        $btn.removeClass("loading");
        var ok = resp && resp.success;
        if (ok) {
          var okMsg = (resp.data && resp.data.message) ? resp.data.message : "Your inquiry has been submitted.";
          $f.replaceWith('<div class="ut-success"><svg class="ut-success-check" viewBox="0 0 56 56" aria-hidden="true"><circle class="ut-success-ring" cx="28" cy="28" r="25"/><path class="ut-success-tick" d="M16 29l8 8 16-17"/></svg><h3 class="ut-success-title">Request sent!</h3><p class="ut-success-sub">' + okMsg + '</p></div>');
        } else {
          $msg.addClass("show err").text(resp && resp.data ? resp.data.message : "Please try again.");
        }
      }).fail(function () { $btn.removeClass("loading"); $msg.addClass("show err").text("Network error — please try again."); });
    });

  });
})(jQuery);
