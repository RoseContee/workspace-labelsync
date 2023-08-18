import ConfigService from './config-service';
import AjaxService from './ajax-service';
import ChromeService from './chrome-service';
import UtilsService from './utils-service';
import { IUsers } from '@/store';

interface ITokenResponse {
  access_token: string;
  refresh_token: string;
}

interface IUserInfo {
  id: string;
  email: string;
  hd: string;
}

interface IDirectoryUsers {
  users: {
    primaryEmail: string;
    name: { fullName: string };
    isAdmin: boolean;
  }[];
  nextPageToken: string;
}

interface IContactGroups {
  contactGroups: { groupType: 'USER_CONTACT_GROUP'; name: string }[];
  nextPageToken: string;
}

class GoogleService {
  private static get_google_oauth_url(state: string) {
    return (
      'https://accounts.google.com/o/oauth2/v2/auth?' +
      new URLSearchParams({
        client_id: ConfigService.GOOGLE_CLIENT_ID,
        response_type: 'code',
        redirect_uri: ConfigService.REDIRECT_URL,
        state: state,
        scope: [
          'openid',
          'email',
          'https://www.googleapis.com/auth/admin.directory.user.readonly',
          'https://www.googleapis.com/auth/contacts',
        ].join(' '),
        prompt: 'consent',
        nonce: UtilsService.generateRandomString(),
        access_type: 'offline',
      })
    );
  }

  private static async getOAuthTokens(request: object) {
    return (await AjaxService.post('https://oauth2.googleapis.com/token', request)) as ITokenResponse;
  }

  private static async refreshAccessToken() {
    const { refresh_token } = (await ChromeService.get({ refresh_token: '' })) as ITokenResponse;
    if (refresh_token) {
      const { access_token } = await this.getOAuthTokens({
        client_id: ConfigService.GOOGLE_CLIENT_ID,
        client_secret: ConfigService.GOOGLE_CLIENT_SECRET,
        refresh_token: refresh_token,
        grant_type: 'refresh_token',
      });
      if (access_token) {
        ChromeService.save({ access_token: access_token });
        return access_token;
      }
    }
    return '';
  }

  private static async login() {
    try {
      const state = UtilsService.generateRandomString();
      const redirect_url = await ChromeService.launchWebAuthFlow(this.get_google_oauth_url(state));
      if (ChromeService.lastError() || !redirect_url) return '';
      const { access_token, refresh_token } = await this.getOAuthTokens({
        code: new URL(redirect_url).searchParams.get('code'),
        client_id: ConfigService.GOOGLE_CLIENT_ID,
        client_secret: ConfigService.GOOGLE_CLIENT_SECRET,
        redirect_uri: ConfigService.REDIRECT_URL,
        grant_type: 'authorization_code',
      });
      if (access_token && refresh_token) {
        ChromeService.save({ access_token: access_token, refresh_token: refresh_token });
        return access_token;
      }
    } catch (ex) {
      console.error('Login error: ', ex);
    }
    return '';
  }

  static async getAccessToken() {
    const { access_token } = (await ChromeService.get({ access_token: '' })) as ITokenResponse;
    if (access_token && (await this.getUserInfo(access_token)).email) {
      return access_token;
    }
    return (await this.refreshAccessToken()) || (await this.login());
  }

  static async getUserInfo(access_token: string) {
    return (await AjaxService.get('https://www.googleapis.com/oauth2/v1/userinfo', {
      Authorization: access_token,
    })) as IUserInfo;
  }

  static async getDirectoryUsers(access_token: string) {
    const users: IUsers = [];
    let directoryUsers: IDirectoryUsers;
    let nextPageToken = '';
    do {
      directoryUsers = (await AjaxService.get('https://admin.googleapis.com/admin/directory/v1/users', {
        params: {
          customer: 'my_customer',
          orderBy: 'givenName',
          pageToken: nextPageToken,
        },
        Authorization: access_token,
      })) as IDirectoryUsers;
      (directoryUsers.users || []).forEach((user) => {
        users.push({ name: user.name.fullName, email: user.primaryEmail, isAdmin: user.isAdmin });
      });
    } while ((nextPageToken = directoryUsers.nextPageToken));
    return users;
  }

  static async getLabels(access_token: string) {
    const labels: string[] = [];
    let groups: IContactGroups;
    let nextPageToken = '';
    do {
      groups = (await AjaxService.get('https://people.googleapis.com/v1/contactGroups', {
        params: {
          groupFields: 'groupType,name',
          pageToken: nextPageToken,
        },
        Authorization: access_token,
      })) as IContactGroups;
      (groups.contactGroups || []).forEach((group) => {
        if (group.groupType === 'USER_CONTACT_GROUP') labels.push(group.name);
      });
    } while ((nextPageToken = groups.nextPageToken));
    return labels;
  }

  static async createLabel(access_token: string, label: string) {
    AjaxService.post(
      'https://people.googleapis.com/v1/contactGroups',
      {
        contactGroup: {
          name: label,
        },
      },
      {
        Authorization: access_token,
      },
    );
  }
}

export default GoogleService;
