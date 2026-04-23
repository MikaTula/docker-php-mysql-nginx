import { ISingerLight } from './singer.interface';
import { IFile } from './file.interface';

export interface ISong {
  id: number;
  name: string;
  singer: ISingerLight;
  year: number;
  createdAt: Date;
  updatedAt: Date | null;
  file: IFile | null;
}

export interface ISongEdit {
  name: string;
  singer_id: number;
  year: number;
  file_id?: number | null;
}

export interface ISongCreate extends ISongEdit {}
