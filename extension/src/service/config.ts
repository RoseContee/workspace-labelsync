export const LABELSYNC_SERVER = 'https://admin.labelsync.it/';
// export const LABELSYNC_SERVER = 'http://localhost:8000/';

export const GOOGLE_CLIENT_ID = '822579701240-7bc0q9qkkd2l85quchv8rjlib77g0aep.apps.googleusercontent.com';
export const GOOGLE_CLIENT_SECRET = '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';

export const getAppInfo = async () => {
  return await chrome.management.getSelf();
};

export const getAppURL = async () => {
  const { id } = await getAppInfo();
  return `https://${id}.chromiumapp.org/`;
};
