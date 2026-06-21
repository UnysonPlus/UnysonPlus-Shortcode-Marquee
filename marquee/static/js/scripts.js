/* Marquee — installable UnysonPlus shortcode.
   The loop itself is pure CSS. This adds the OPTIONAL "React to Scroll" reaction
   for instances marked data-mq-velocity="1": it nudges the CSS animation's
   playbackRate up with scroll speed (and flips with scroll direction), then
   eases back to a steady loop. Vanilla JS; skips in the builder + reduced motion. */
(function () {
  'use strict';
  if (typeof window === 'undefined' || typeof document === 'undefined') return;

  function inBuilder() {
    return document.body && (
      document.body.classList.contains('fw-builder-active') ||
      document.body.classList.contains('fw-backend-builder') ||
      window.self !== window.top
    );
  }
  var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var now = function () { return window.performance && performance.now ? performance.now() : Date.now(); };

  function init() {
    if (inBuilder() || reduced) return;
    var nodes = document.querySelectorAll('.fw-mq[data-mq-velocity="1"]');
    if (!nodes.length) return;

    var tracks = [];
    nodes.forEach(function (mq) { var t = mq.querySelector('.fw-mq__track'); if (t) tracks.push(t); });
    if (!tracks.length) return;

    var lastY = window.pageYOffset || 0, lastT = now(), settle = null;

    function anim(t) { return t.getAnimations && t.getAnimations()[0]; }

    window.addEventListener('scroll', function () {
      var t0 = now(), y = window.pageYOffset || 0;
      var dt = Math.max(16, t0 - lastT);
      var v = (y - lastY) / dt;            // px/ms
      lastY = y; lastT = t0;

      var factor = Math.min(6, 1 + Math.abs(v) * 6);
      var sign = v < 0 ? -1 : 1;
      tracks.forEach(function (t) { var a = anim(t); if (a) a.playbackRate = sign * factor; });

      clearTimeout(settle);
      settle = setTimeout(function () {
        tracks.forEach(function (t) { var a = anim(t); if (a) a.playbackRate = 1; });
      }, 150);
    }, { passive: true });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
