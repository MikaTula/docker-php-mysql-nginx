
export function parseNumericFormInput(value: unknown): number | null {
  if (value === null || value === undefined) {
    return null;
  }
  if (typeof value === 'number') {
    return Number.isNaN(value) ? null : value;
  }
  if (typeof value === 'string') {
    const trimmed = value.trim();
    if (trimmed === '') {
      return null;
    }
    const n = Number(trimmed);
    return Number.isNaN(n) ? null : n;
  }
  return null;
}
