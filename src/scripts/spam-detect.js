$(function () {
  const isTargetResolution =
    window.screen.width === 800 && window.screen.height === 600;
  const isFromGoogle = document.referrer.includes("google.com");
  const isChrome = navigator.userAgent.includes("Chrome/");

  console.log("check", {
    isTargetResolution,
    isFromGoogle,
    isChrome,
  });

  if (isTargetResolution && isFromGoogle && isChrome) {
    window.location.href = "/?bot_trap=1";
  }
});
