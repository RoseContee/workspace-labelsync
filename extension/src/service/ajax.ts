/* eslint-disable @typescript-eslint/no-explicit-any */

class AjaxService {
  private static fetch(url: string, config: any) {
    return new Promise((resolve) => {
      const requestURL = new URL(url);
      if (config.params) {
        Object.keys(config.params).forEach((key) => {
          const value = config.params[key];
          if (Array.isArray(value)) {
            value.forEach((val) => requestURL.searchParams.append(key, val));
          } else {
            requestURL.searchParams.append(key, value);
          }
        });
      }
      fetch(requestURL, {
        ...config,
        headers: {
          accept: 'application/json, text/plain, */*',
          'content-type': 'application/json',
          ...(config.Authorization ? { Authorization: `Bearer ${config.Authorization}` } : {}),
        },
      })
        .then(async (response) => {
          try {
            resolve(await response.json());
          } catch {
            resolve({});
          }
        })
        .catch(() => {
          resolve({});
        });
    });
  }

  static get(url: string, config: any = {}) {
    return this.fetch(url, { ...config, method: 'GET' });
  }

  static post(url: string, body: any, config: any = {}) {
    return this.fetch(url, { ...config, method: 'POST', body: JSON.stringify(body) });
  }
}

export default AjaxService;
