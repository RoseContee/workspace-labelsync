import ConfigService from './config-service';
import AjaxService from './ajax-service';

class LabelsyncService {
  static baseURL = `${ConfigService.SERVER_URL}/api`;

  static async checkLicenseKey(key: string) {
    await AjaxService.get(`${this.baseURL}/validate-key`, {
      params: { key: key },
    });
  }

  static adminSync(email: string, users: string[], labels: string[]) {
    AjaxService.post(`${this.baseURL}/admin-sync`, {
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
