import ConfigService from './config-service';
import AjaxService from './ajax-service';
import ChromeService from './chrome-service';
import UtilsService from './utils-service';

export interface ILicenseResponse {
  success: boolean;
  expires_on: number;
  error: string;
}

class LabelsyncService {
  static baseURL = `${UtilsService.trim(ConfigService.SERVER_URL, '/')}/api/app/v1`;

  static async submitLicenseKey(email: string, licenseKey: string) {
    return (await AjaxService.post(`${this.baseURL}/submit-key`, {
      email: email,
      key: licenseKey,
    })) as ILicenseResponse;
  }

  static async recoverLicenseKey(email: string) {
    return (await AjaxService.post(`${this.baseURL}/recover-key`, {
      email: email,
    })) as { licenseKey: string };
  }

  static async adminSync(email: string, users: string[], labels: string[]) {
    const { licenseKey } = await ChromeService.get({ licenseKey: '' });
    if (!licenseKey) return;
    AjaxService.post(`${this.baseURL}/admin-sync`, {
      key: licenseKey,
      email: email,
      users: users,
      labels: labels,
    });
  }

  static async getSyncLabels(email: string) {
    const response = (await AjaxService.get(`${this.baseURL}/labels`, {
      params: { email: email },
    })) as { labels: string[] };
    return response.labels ?? [];
  }
}

export default LabelsyncService;
