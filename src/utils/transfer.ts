export function transferStringToList (str: string, splitSymbol: string, count?: number) {
  if (!str.length) {
    return
  }
  const list = str.split(splitSymbol)
  if (list && count) {
    return list.slice(0, count)
  }
  return list
}
