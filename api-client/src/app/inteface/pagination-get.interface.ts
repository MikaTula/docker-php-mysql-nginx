export interface IPaginationGet {
  page: number;
  size: number;
  sortBy: string;
  sortOrder: 'asc' | 'desc';
}
