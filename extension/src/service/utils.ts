export const generateRandomString = (length: number = 32) => {
  const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  const charLength = chars.length;
  let result = '';
  for (let i = 0; i < length; i++) {
    result += chars.charAt(Math.floor(Math.random() * charLength));
  }
  return result;
};

export const dateFormat = (time: number) => {
  const date = new Date(time);
  return `${date.getMonth() + 1}/${date.getDate()}/${date.getFullYear()}`;
};

export const trim = (value: string, charsets: string) => {
  return rtrim(ltrim(value, charsets), charsets);
};

export const ltrim = (value: string, charsets: string) => {
  [...charsets].forEach((ch) => {
    if (value[0] == ch) value = value.substring(1);
  });
  return value;
};

export const rtrim = (value: string, charsets: string) => {
  [...charsets].forEach((ch) => {
    if (value[value.length - 1] == ch) value = value.substring(0, value.length - 1);
  });
  return value;
};

export const sleep = (time: number) => new Promise((r) => setTimeout(r, time));
