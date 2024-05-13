export const isoDateGenerator = () => {
  // 現在の日付を取得
  const currentDate = new Date();

  // 1週間前の日付
  //const oneWeekAgo = new Date(currentDate.getTime() - (7 * 24 * 60 * 60 * 1000));
  // 1日前の日付
  const oneDayAgo = new Date(currentDate.getTime() - (1 * 24 * 60 * 60 * 1000));

  // 現在の日付をISOフォーマットに変換
  return oneDayAgo.toISOString();
  //console.log(currentDateISO,  oneWeekAgo,  currentDate)
}