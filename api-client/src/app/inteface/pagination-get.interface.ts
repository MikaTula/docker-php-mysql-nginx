export interface IPaginationGet {
  page: number;
  size: number;
  sortBy: string;
  sortOrder: 'asc' | 'desc';
}

export interface IPaginationResult<T> {
  items: T[];
  page: number;
  size: number;
  total: number;
}
