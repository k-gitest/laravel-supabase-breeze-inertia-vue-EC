const isoDateGenerator = () => {
  const currentDate = /* @__PURE__ */ new Date();
  const oneDayAgo = new Date(currentDate.getTime() - 1 * 24 * 60 * 60 * 1e3);
  return oneDayAgo.toISOString();
};
export {
  isoDateGenerator as i
};
