/* eslint-disable @typescript-eslint/no-explicit-any */
class AjaxService {
  private static fetch(url: string, config: any) {
    return new Promise((resolve) => {
      fetch(url, {
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
