export interface IBaseResponse<T> {
  success: boolean;
  data: T;
  message: string;
}
