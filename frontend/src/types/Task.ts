
// Describes the structure of a task
export interface Task {
  id: number;
  title: string;
  description?: string | null;
  priority: "low" | "medium" | "high";
  due_date?: string | null;
  tags?: string | null;
  list_id: number;
  order:number;
}