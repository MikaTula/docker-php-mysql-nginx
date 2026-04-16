import { ISingerLight } from './singer.interface';

export interface ISong {
  id: number;
  name: string;
  singer: ISingerLight;
  year: number;
  createdAt: Date;
  updatedAt: Date | null;
}

export interface ISongEdit {
  name: string;
  singer_id: number;
  year: number;
}

export interface ISongCreate extends ISongEdit {}
