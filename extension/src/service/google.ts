import { GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, getAppURL } from './config';
import AjaxService from './ajax';
import ChromeService from './chrome';
import { generateRandomString, sleep } from './utils';
import { IUser } from '@/store';

interface ITokenResponse {
  access_token: string;
  refresh_token: string;
}

interface IUserInfoResponse {
  id: string;
  email: string;
  hd: string;
}

interface IDirectoryUsersResponse {
  users: {
    primaryEmail: string;
    name: { fullName: string };
    isAdmin: boolean;
  }[];
  nextPageToken: string;
}

interface IContactGroup {
  resourceName: string;
  groupType?: 'USER_CONTACT_GROUP';
  name: string;
  memberCount?: number;
  memberResourceNames: string[];
}

interface IContactGroupsResponse {
  contactGroups: IContactGroup[];
  nextPageToken: string;
}

type IPersonField =
  | 'addresses'
  | 'biographies'
  | 'birthdays'
  | 'calendarUrls'
  | 'clientData'
  | 'emailAddresses'
  | 'events'
  | 'externalIds'
  | 'fileAses'
  | 'genders'
  | 'imClients'
  | 'interests'
  | 'locales'
  | 'locations'
  | 'miscKeywords'
  | 'names'
  | 'nicknames'
  | 'occupations'
  | 'organizations'
  | 'phoneNumbers'
  | 'relations'
  | 'sipAddresses'
  | 'skills'
  | 'urls'
  | 'userDefined';

interface IAddress {
  type: string;
  poBox: string;
  streetAddress: string;
  extendedAddress: string;
  city: string;
  region: string;
  postalCode: string;
  country: string;
  countryCode: string;
}

interface IBiography {
  value: string;
  contentType: string;
}

interface IBirthday {
  date: unknown;
}

interface ICalendarUrl {
  url: string;
  type: string;
}

interface IClientData {
  key: string;
  value: string;
}

interface IEmailAddress {
  metadata: {
    primary: boolean;
  };
  value: string;
  type: string;
  displayName: string;
}

interface IEvent {
  date: unknown;
  type: string;
}

interface IExternalId {
  value: string;
  type: string;
}

interface IFileAs {
  value: string;
}

interface IGender {
  value: string;
  addressMeAs: string;
}

interface IImClient {
  username: string;
  type: string;
  protocol: string;
}

interface IInterest {
  value: string;
}

interface ILocale {
  value: string;
}

interface ILocation {
  value: string;
  type: string;
  current: string;
  buildingId: string;
  floor: string;
  floorSection: string;
  deskCode: string;
}

interface IMiscKeyword {
  value: string;
  type: string;
}

interface IName {
  unstructuredName: string;
  familyName: string;
  givenName: string;
  middleName: string;
  honorificPrefix: string;
  honorificSuffix: string;
  phoneticFullName: string;
  phoneticFamilyName: string;
  phoneticGivenName: string;
  phoneticMiddleName: string;
  phoneticHonorificPrefix: string;
  phoneticHonorificSuffix: string;
}

interface INickName {
  value: string;
  type: string;
}

interface IOccupation {
  value: string;
}

interface IOrganization {
  type: string;
  startDate: unknown;
  endDate: unknown;
  current: boolean;
  name: string;
  phoneticName: string;
  department: string;
  title: string;
  jobDescription: string;
  symbol: string;
  domain: string;
  location: string;
  costCenter: string;
  fullTimeEquivalentMillipercent: number;
}

interface IPhoneNumber {
  value: string;
  type: string;
}

interface IRelation {
  person: string;
  type: string;
}

interface ISipAddress {
  value: string;
  type: string;
}

interface ISkill {
  value: string;
}

interface IUrl {
  value: string;
  type: string;
}

interface IUserDefined {
  key: string;
  value: string;
}

export interface IPerson {
  addresses?: IAddress[];
  biographies?: IBiography[];
  birthdays?: IBirthday[];
  calendarUrls?: ICalendarUrl[];
  clientData?: IClientData[];
  emailAddresses?: IEmailAddress[];
  events?: IEvent[];
  externalIds?: IExternalId[];
  fileAses?: IFileAs[];
  genders?: IGender[];
  imClients?: IImClient[];
  interests?: IInterest[];
  locales?: ILocale[];
  locations?: ILocation[];
  miscKeywords?: IMiscKeyword[];
  names?: IName[];
  nicknames?: INickName[];
  occupations?: IOccupation[];
  organizations?: IOrganization[];
  phoneNumbers?: IPhoneNumber[];
  relations?: IRelation[];
  sipAddresses?: ISipAddress[];
  skills?: ISkill[];
  urls?: IUrl[];
  userDefined?: IUserDefined[];
}

export interface IGroupPersons {
  [group: string]: {
    resourceName?: string;
    persons: IPerson[];
  };
}

const personFields: IPersonField[] = [
  'addresses',
  'biographies',
  'birthdays',
  'calendarUrls',
  'clientData',
  'emailAddresses',
  'events',
  'externalIds',
  'genders',
  'imClients',
  'interests',
  'locales',
  'locations',
  'miscKeywords',
  'names',
  'nicknames',
  'occupations',
  'organizations',
  'phoneNumbers',
  'relations',
  'sipAddresses',
  'skills',
  'urls',
  'userDefined',
];

class GoogleService {
  private static async get_google_oauth_url(state: string) {
    return (
      'https://accounts.google.com/o/oauth2/v2/auth?' +
      new URLSearchParams({
        client_id: GOOGLE_CLIENT_ID,
        response_type: 'code',
        redirect_uri: await getAppURL(),
        state: state,
        scope: [
          'openid',
          'email',
          'https://www.googleapis.com/auth/admin.directory.user.readonly',
          'https://www.googleapis.com/auth/contacts',
        ].join(' '),
        prompt: 'consent',
        nonce: generateRandomString(),
        access_type: 'offline',
      })
    );
  }

  private static async getOAuthTokens(request: object) {
    return (await AjaxService.post('https://oauth2.googleapis.com/token', request)) as ITokenResponse;
  }

  private static async login() {
    try {
      const authURL = await this.get_google_oauth_url(generateRandomString());
      const redirect_url = await ChromeService.launchWebAuthFlow(authURL);
      if (ChromeService.lastError() || !redirect_url) return '';
      const { access_token, refresh_token } = await this.getOAuthTokens({
        code: new URL(redirect_url).searchParams.get('code'),
        client_id: GOOGLE_CLIENT_ID,
        client_secret: GOOGLE_CLIENT_SECRET,
        redirect_uri: await getAppURL(),
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

  private static async refreshAccessToken() {
    const { refresh_token } = (await ChromeService.get({ refresh_token: '' })) as ITokenResponse;
    if (refresh_token) {
      const { access_token } = await this.getOAuthTokens({
        client_id: GOOGLE_CLIENT_ID,
        client_secret: GOOGLE_CLIENT_SECRET,
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
    })) as IUserInfoResponse;
  }

  static async getDirectoryUsers(access_token: string) {
    const users: IUser[] = [];
    try {
      let directoryUsers: IDirectoryUsersResponse;
      let nextPageToken = '';
      do {
        directoryUsers = (await AjaxService.get('https://admin.googleapis.com/admin/directory/v1/users', {
          params: {
            customer: 'my_customer',
            orderBy: 'givenName',
            ...(nextPageToken ? { pageToken: nextPageToken } : {}),
          },
          Authorization: access_token,
        })) as IDirectoryUsersResponse;
        (directoryUsers.users || []).forEach((user) => {
          users.push({ name: user.name.fullName, email: user.primaryEmail, isAdmin: user.isAdmin });
        });
      } while ((nextPageToken = directoryUsers.nextPageToken));
    } catch (ex) {
      console.error('getDirectoryUser error: ', ex);
    }
    return users;
  }

  static async getGroupPersons(access_token: string) {
    const labels: IGroupPersons = {};
    try {
      let groups: IContactGroupsResponse;
      let nextPageToken = '';
      do {
        groups = (await AjaxService.get('https://people.googleapis.com/v1/contactGroups', {
          params: {
            groupFields: 'groupType,name,memberCount',
            ...(nextPageToken ? { pageToken: nextPageToken } : {}),
          },
          Authorization: access_token,
        })) as IContactGroupsResponse;
        const contactGroups = groups.contactGroups || [];
        for (let i = 0; i < contactGroups.length; i++) {
          const group = contactGroups[i];
          if (group.groupType === 'USER_CONTACT_GROUP') {
            labels[group.name] = {
              resourceName: group.resourceName,
              persons: [],
            };
            const { memberResourceNames } = (await AjaxService.get(
              `https://people.googleapis.com/v1/${group.resourceName}`,
              {
                params: {
                  maxMembers: group.memberCount ?? 0,
                  groupFields: 'name',
                },
                Authorization: access_token,
              },
            )) as IContactGroup;
            let members: string[];
            let j = 0;
            while ((members = (memberResourceNames ?? []).slice(j, (j += 200))).length) {
              const { responses } = (await AjaxService.get('https://people.googleapis.com/v1/people:batchGet', {
                params: {
                  resourceNames: members,
                  personFields: personFields.join(','),
                  sources: ['READ_SOURCE_TYPE_CONTACT'],
                },
                Authorization: access_token,
              })) as { responses: { person: IPerson }[] };
              (responses ?? []).forEach(({ person }) => {
                if (person) {
                  labels[group.name].persons.push(
                    personFields.reduce(
                      (obj, field) => ({
                        ...obj,
                        ...this.personInfo.get(field)?.(field, person[field]),
                      }),
                      {},
                    ),
                  );
                }
              });
            }
            await sleep(1000);
          }
        }
      } while ((nextPageToken = groups.nextPageToken));
    } catch (ex) {
      console.error('getGroupPersons error: ', ex);
    }
    return labels;
  }

  static async createLabel(access_token: string, label: string) {
    const { resourceName } = (await AjaxService.post(
      'https://people.googleapis.com/v1/contactGroups',
      {
        contactGroup: {
          name: label,
        },
      },
      {
        Authorization: access_token,
      },
    )) as { resourceName: string };
    return resourceName ?? null;
  }

  static async searchContact(access_token: string, email: string) {
    const { results } = (await AjaxService.get('https://people.googleapis.com/v1/people:searchContacts', {
      params: {
        query: email,
        readMask: 'emailAddresses,names',
        sources: ['READ_SOURCE_TYPE_CONTACT'],
      },
      Authorization: access_token,
    })) as { results: { person: { resourceName: string } }[] };
    return (results || [])[0]?.person?.resourceName ?? null;
  }

  static async createContact(access_token: string, personInfo: object) {
    const { resourceName } = (await AjaxService.post(
      'https://people.googleapis.com/v1/people:createContact',
      personInfo,
      {
        params: {
          personFields: 'emailAddresses,names',
          sources: ['READ_SOURCE_TYPE_CONTACT'],
        },
        Authorization: access_token,
      },
    )) as { resourceName: string };
    return resourceName ?? null;
  }

  static async addContactToLabel(access_token: string, label: string, contact: string) {
    return await AjaxService.post(
      `https://people.googleapis.com/v1/${label}/members:modify`,
      {
        resourceNamesToAdd: [contact],
      },
      {
        Authorization: access_token,
      },
    );
  }

  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  static personInfo = new Map<IPersonField, (field: IPersonField, person?: object[]) => object>([
    [
      'addresses',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IAddress;
            return {
              type: o.type,
              poBox: o.poBox,
              streetAddress: o.streetAddress,
              extendedAddress: o.extendedAddress,
              city: o.city,
              region: o.region,
              postalCode: o.postalCode,
              country: o.country,
              countryCode: o.countryCode,
            };
          }),
        };
      },
    ],
    [
      'biographies',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IBiography;
            return {
              value: o.value,
              contentType: o.contentType,
            };
          }),
        };
      },
    ],
    [
      'birthdays',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IBirthday;
            return {
              date: o.date,
            };
          }),
        };
      },
    ],
    [
      'calendarUrls',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as ICalendarUrl;
            return {
              url: o.url,
              type: o.type,
            };
          }),
        };
      },
    ],
    [
      'clientData',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IClientData;
            return {
              key: o.key,
              value: o.value,
            };
          }),
        };
      },
    ],
    [
      'emailAddresses',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IEmailAddress;
            return {
              metadata: {
                primary: o.metadata.primary,
              },
              value: o.value,
              type: o.type,
              displayName: o.displayName,
            };
          }),
        };
      },
    ],
    [
      'events',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IEvent;
            return {
              date: o.date,
              type: o.type,
            };
          }),
        };
      },
    ],
    [
      'externalIds',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IExternalId;
            return {
              value: o.value,
              type: o.type,
            };
          }),
        };
      },
    ],
    [
      'fileAses',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IFileAs;
            return {
              value: o.value,
            };
          }),
        };
      },
    ],
    [
      'genders',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IGender;
            return {
              value: o.value,
              addressMeAs: o.addressMeAs,
            };
          }),
        };
      },
    ],
    [
      'imClients',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IImClient;
            return {
              username: o.username,
              type: o.type,
              protocol: o.protocol,
            };
          }),
        };
      },
    ],
    [
      'interests',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IInterest;
            return {
              value: o.value,
            };
          }),
        };
      },
    ],
    [
      'locales',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as ILocale;
            return {
              value: o.value,
            };
          }),
        };
      },
    ],
    [
      'locations',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as ILocation;
            return {
              value: o.value,
              type: o.type,
              current: o.current,
              buildingId: o.buildingId,
              floor: o.floor,
              floorSection: o.floorSection,
              deskCode: o.deskCode,
            };
          }),
        };
      },
    ],
    [
      'miscKeywords',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IMiscKeyword;
            return {
              value: o.value,
              type: o.type,
            };
          }),
        };
      },
    ],
    [
      'names',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IName;
            return {
              unstructuredName: o.unstructuredName,
              familyName: o.familyName,
              givenName: o.givenName,
              middleName: o.middleName,
              honorificPrefix: o.honorificPrefix,
              honorificSuffix: o.honorificSuffix,
              phoneticFullName: o.phoneticFullName,
              phoneticFamilyName: o.phoneticFamilyName,
              phoneticGivenName: o.phoneticGivenName,
              phoneticMiddleName: o.phoneticMiddleName,
              phoneticHonorificPrefix: o.phoneticHonorificPrefix,
              phoneticHonorificSuffix: o.phoneticHonorificSuffix,
            };
          }),
        };
      },
    ],
    [
      'nicknames',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as INickName;
            return {
              value: o.value,
              type: o.type,
            };
          }),
        };
      },
    ],
    [
      'occupations',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IOccupation;
            return {
              value: o.value,
            };
          }),
        };
      },
    ],
    [
      'organizations',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IOrganization;
            return {
              type: o.type,
              startDate: o.startDate,
              endDate: o.endDate,
              current: o.current,
              name: o.name,
              phoneticName: o.phoneticName,
              department: o.department,
              title: o.title,
              jobDescription: o.jobDescription,
              symbol: o.symbol,
              domain: o.domain,
              location: o.location,
              costCenter: o.costCenter,
              fullTimeEquivalentMillipercent: o.fullTimeEquivalentMillipercent,
            };
          }),
        };
      },
    ],
    [
      'phoneNumbers',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IPhoneNumber;
            return {
              value: o.value,
              type: o.type,
            };
          }),
        };
      },
    ],
    [
      'relations',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IRelation;
            return {
              person: o.person,
              type: o.type,
            };
          }),
        };
      },
    ],
    [
      'sipAddresses',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as ISipAddress;
            return {
              value: o.value,
              type: o.type,
            };
          }),
        };
      },
    ],
    [
      'skills',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as ISkill;
            return {
              value: o.value,
            };
          }),
        };
      },
    ],
    [
      'urls',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IUrl;
            return {
              value: o.value,
              type: o.type,
            };
          }),
        };
      },
    ],
    [
      'userDefined',
      (field: IPersonField, obj?: object[]) => {
        if (!obj) return {};
        return {
          [field]: obj.map((item) => {
            const o = item as IUserDefined;
            return {
              key: o.key,
              value: o.value,
            };
          }),
        };
      },
    ],
  ]);
}

export default GoogleService;
