import ChromeService from '@/service/chrome';
import GoogleService, { IGroupPersons, IPerson } from '@/service/google';
import AdminLabelsyncItService from '@/service/admin.labelsync.it';
import { sleep } from '@/service/utils';
import { IUser } from '@/store';

const SYNC_PERIOD = 5; //60 * 12 // minutes

const createAlarm = () => {
  chrome.alarms.create('labelsync', {
    delayInMinutes: 0,
    periodInMinutes: SYNC_PERIOD,
  });
};

chrome.runtime.onInstalled.addListener(async ({ reason: r }) => {
  if (r == 'install') createAlarm();
});

chrome.runtime.onStartup.addListener(async () => {
  const { lastSyncTime } = await ChromeService.get({ lastSyncTime: 0 });
  if (Date.now() - lastSyncTime > SYNC_PERIOD) createAlarm();
});

chrome.alarms.onAlarm.addListener(async (alarm) => {
  console.log('Running Labelsync...');
  ChromeService.save({ lastSyncTime: alarm.scheduledTime });
  runSync();
});

const runSync = async () => {
  const access_token = await GoogleService.getAccessToken();
  if (!access_token) return;
  const { email } = await GoogleService.getUserInfo(access_token);
  const users = await GoogleService.getDirectoryUsers(access_token);
  const groupPersons = await GoogleService.getGroupPersons(access_token);
  if (users.find((user) => user.email === email && user.isAdmin)) {
    syncAdmin(email, users, groupPersons);
  } else {
    syncUser(access_token, email, groupPersons);
  }
};

const syncAdmin = async (email: string, users: IUser[], groupPersons: IGroupPersons) => {
  try {
    const { selectedUsers } = await ChromeService.get({ selectedUsers: [] });
    let newUsers = [...selectedUsers];
    (selectedUsers as string[]).forEach((email) => {
      if (!users.find((user) => user.email == email && !user.isAdmin)) {
        newUsers = newUsers.filter((user) => user != email);
      }
    });
    for (const key in groupPersons) {
      delete groupPersons[key].resourceName;
    }
    AdminLabelsyncItService.adminSync(email, newUsers, JSON.stringify(groupPersons));
  } catch (ex) {
    console.error('syncAdmin error: ', ex);
  }
};

const syncUser = async (access_token: string, email: string, myGroupPersons: IGroupPersons) => {
  try {
    const adminGroups = await AdminLabelsyncItService.getSyncLabels(email);
    const myLabels = Object.keys(myGroupPersons);
    for (let i = 0; i < adminGroups.length; i++) {
      const syncGroupPersons = JSON.parse(adminGroups[i]) as IGroupPersons;
      const syncLabels = Object.keys(syncGroupPersons);
      for (let j = 0; j < syncLabels.length; j++) {
        const syncLabel = syncLabels[j];
        const syncPersons = syncGroupPersons[syncLabel].persons;
        let myLabelResource: string;
        let myPersons: IPerson[] = [];
        if (myLabels.includes(syncLabel)) {
          myLabelResource = myGroupPersons[syncLabel].resourceName ?? '';
          myPersons = myGroupPersons[syncLabel].persons;
        } else {
          myLabelResource = await GoogleService.createLabel(access_token, syncLabel);
        }
        if (!myLabelResource || !syncPersons || !Array.isArray(syncPersons)) {
          return;
        }
        for (let k = 0; k < syncPersons.length; k++) {
          const syncPerson = syncPersons[k];
          if (syncPerson && !isExistsPerson(syncPerson, myPersons)) {
            const personEmail = (syncPerson.emailAddresses || []).find((email) => email.metadata.primary)?.value;
            if (!personEmail) return;
            let myPeopleResource = await GoogleService.searchContact(access_token, personEmail);
            if (
              !myPeopleResource &&
              (syncPerson.biographies ||
                syncPerson.birthdays ||
                syncPerson.genders ||
                syncPerson.names ||
                syncPerson.emailAddresses)
            ) {
              myPeopleResource = await GoogleService.createContact(access_token, syncPerson);
            }
            if (myLabelResource && myPeopleResource) {
              await GoogleService.addContactToLabel(access_token, myLabelResource, myPeopleResource);
              await sleep(1000);
            }
          }
        }
      }
    }
  } catch (ex) {
    console.error('syncUser error: ', ex);
  }
};

const isExistsPerson = (person: IPerson, persons: IPerson[]) => {
  let exists = false;
  (person.emailAddresses || []).forEach((email) => {
    if (persons.find((person) => (person.emailAddresses || []).find((item) => item.value === email.value))) {
      exists = true;
    }
  });
  return exists;
};
