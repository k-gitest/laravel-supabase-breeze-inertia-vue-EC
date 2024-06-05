export const isoDateGenerator = () => {
  const currentDate = new Date();
  const oneDayAgo = new Date(currentDate.getTime() - (1 * 24 * 60 * 60 * 1000));

  return oneDayAgo.toISOString();
}