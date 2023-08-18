interface IStorageItem {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  [key: string]: any;
}

const ChromeService = {
  launchWebAuthFlow: (url: string) => chrome.identity.launchWebAuthFlow({ url: url, interactive: true }),
  save: (item: IStorageItem) => chrome.storage.local.set(item),
  get: async (item: IStorageItem) => await chrome.storage.local.get(item),
  remove: (keys: string | string[]) => chrome.storage.local.remove(keys),
  send: (message: object) => chrome.runtime.sendMessage(message, () => chrome.runtime.lastError),
  lastError: () => chrome.runtime.lastError,
};

export default ChromeService;
