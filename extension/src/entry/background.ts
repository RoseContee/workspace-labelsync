import ChromeService from '@/service/chrome-service';
import GoogleService from '@/service/google-service';
import LabelsyncService from '@/service/labelsync-service';
import { IUser } from '@/store';

const SYNC_PERIOD = 1; //60 * 12 // minutes

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
  const access_token = await GoogleService.getAccessToken();
  if (!access_token) return;
  const users = await GoogleService.getDirectoryUsers(access_token);
  const { email } = await GoogleService.getUserInfo(access_token);
  const labels = await GoogleService.getLabels(access_token);
  if (users.find((user) => user.email === email && user.isAdmin)) {
    adminSync(email, users, labels);
  } else {
    userSync(access_token, email, labels);
  }
});

const adminSync = async (email: string, users: IUser[], labels: string[]) => {
  const { selectedUsers } = await ChromeService.get({ selectedUsers: [] });
  let newUsers = [...selectedUsers];
  (selectedUsers as string[]).forEach((email) => {
    if (!users.find((user) => user.email == email && !user.isAdmin)) {
      newUsers = newUsers.filter((user) => user != email);
    }
  });
  LabelsyncService.adminSync(email, newUsers, labels);
};

const userSync = async (access_token: string, email: string, MyLabels: string[]) => {
  const syncLabels = await LabelsyncService.getSyncLabels(email);
  syncLabels.forEach(async (label) => {
    if (!MyLabels.includes(label)) {
      await GoogleService.createLabel(access_token, label);
    }
  });
};
