import ConfigService from './config-service';
import AjaxService from './ajax-service';
import ChromeService from './chrome-service';
import UtilsService from './utils-service';

interface IUser {
  primaryEmail: string;
  name: {
    fullName: string;
  };
}

interface IContactGroup {
  groupType: 'GROUP_TYPE_UNSPECIFIED' | 'USER_CONTACT_GROUP' | 'SYSTEM_CONTACT_GROUP';
  name: string;
  formattedName: string;
  memberCount: number;
}

class GoogleSocial {
  static async login() {
    const token = UtilsService.generateRandomString();
    const redirect_url = await ChromeService.launchWebAuthFlow(this.get_google_oauth_url(token));
    console.log(redirect_url);
    if (ChromeService.lastError() || !redirect_url) return null;
    const redirect = new URL(redirect_url);
    if (token != redirect.searchParams.get('state')) return null;
    const code = redirect.searchParams.get('code');
    const { access_token, refresh_token } = (await AjaxService.post('https://oauth2.googleapis.com/token', {
      code: code,
      client_id: ConfigService.GOOGLE_CLIENT_ID,
      // client_secret: ConfigService.GOOGLE_CLIENT_SECRET,
      redirect_uri: ConfigService.REDIRECT_URL,
      grant_type: 'authorization_code',
    })) as { access_token: string; refresh_token: string };
    console.log(access_token);
    ChromeService.save({ access_token: access_token, refresh_token: refresh_token });
    return access_token;
  }
  static async refreshAccessToken() {
    const { refresh_token } = await ChromeService.get({ refresh_token: null });
    if (refresh_token) {
      const { access_token } = (await AjaxService.post('https://oauth2.googleapis.com/token', {
        client_id: ConfigService.GOOGLE_CLIENT_ID,
        // client_secret: ConfigService.GOOGLE_CLIENT_SECRET,
        refresh_token: refresh_token,
        grant_type: 'refresh_token',
      })) as { access_token: string };
      ChromeService.save({ access_token: access_token });
      return access_token || null;
    }
    return null;
  }
  static async getAccessToken() {
    const { access_token } = await ChromeService.get({ access_token: null });
    const { id } = (await AjaxService.get('https://www.googleapis.com/oauth2/v1/userinfo', {
      Authorization: access_token,
    })) as { id: string };
    if (id) return access_token;
    return (await this.refreshAccessToken()) || (await this.login());
  }
  static async getData() {
    try {
      const access_token = await this.getAccessToken();
      const { users } = (await AjaxService.get('https://admin.googleapis.com/admin/directory/v1/users', {
        Authorization: access_token,
      })) as { users: IUser[] };
      const { contactGroups } = (await AjaxService.get('https://people.googleapis.com/v1/contactGroups', {
        Authorization: access_token,
      })) as { contactGroups: IContactGroup[] };
      return {
        users: (users ?? []).map((item) => ({ email: item.primaryEmail, name: item.name.fullName })),
        labels: (contactGroups ?? []).filter((item) => item.groupType === 'USER_CONTACT_GROUP'),
      };
    } catch {
      return {
        users: [],
        labels: [],
      };
    }
  }
  static get_google_oauth_url(state: string) {
    return `https://accounts.google.com/o/oauth2/v2/auth?client_id=${encodeURIComponent(ConfigService.GOOGLE_CLIENT_ID)}
&response_type=code
&redirect_uri=${encodeURIComponent(ConfigService.REDIRECT_URL)}
&state=${encodeURIComponent(state)}
&scope=${encodeURIComponent(
      'openid https://www.googleapis.com/auth/admin.directory.user https://www.googleapis.com/auth/admin.directory.user.readonly https://www.googleapis.com/auth/contacts',
    )}
&prompt=consent
&nonce=${encodeURIComponent(Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15))}
&access_type=offline`;
  }
}

export default GoogleSocial;
