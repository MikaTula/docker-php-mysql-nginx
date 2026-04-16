import { IPaginationGet } from '../inteface/pagination-get.interface';
import { HttpParams } from '@angular/common/http';

export class StringUtils {
  public static getStringFromPagination(data: IPaginationGet): HttpParams {
    return new HttpParams()
      .append('page', String(data.page))
      .append('size', String(data.size))
      .append('sortBy', data.sortBy)
      .append('sortOrder', data.sortOrder);
  }
}
