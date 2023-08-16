import GoogleSocial from '@/service/google-service';

chrome.runtime.onInstalled.addListener(async ({ reason: r }) => {
  if (r == 'install') {
    GoogleSocial.login();
  }
});
