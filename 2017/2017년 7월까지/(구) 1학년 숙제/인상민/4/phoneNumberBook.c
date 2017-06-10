#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#define MAX_NUM 10
#define MAX_LEN 20

void add_PhoneNum(int *count, char PhoneNumberBook[MAX_NUM][2][MAX_LEN]);
void del_PhoneNum(int *count, char PhoneNumberBook[MAX_NUM][2][MAX_LEN]);
void printAll_PhoneNum(int *count, char PhoneNumberBook[MAX_NUM][2][MAX_LEN]);
void edit_PhoneNum(int *count, char PhoneNumberBook[MAX_NUM][2][MAX_LEN]);

int main(void)
{
	int select = 0, count = 0;
	char PhoneNumberBook[MAX_NUM][2][MAX_LEN];
	while (1)
	{
		printf("원하는 작업을 선택하세요. (1 : 추가, 2 : 삭제, 3 : 모두 보기, 4 : 수정, 5 : 끝내기) >>");
		scanf("%d", &select);
		
		switch(select)
		{
			case 1 :
				add_PhoneNum(&count, PhoneNumberBook);
				break;
			case 2 :
				del_PhoneNum(&count, PhoneNumberBook);
				break;
			case 3 :
				printAll_PhoneNum(&count, PhoneNumberBook);
				break;
			case 4 :
				edit_PhoneNum(&count, PhoneNumberBook);
				break;
			case 5 :
				return 0;
			default : 
				printf("올바른 선택지를 선택하세요.");
				break;
		}
	}
}

void add_PhoneNum(int *count, char PhoneNumberBook[MAX_NUM][2][MAX_LEN])
{
	if (*count == 10)
		printf("전화번호부가 꽉 찼습니다.\n");
		
	else
	{
		char Name[20], PhoneNumber[20];
		int i;
		printf("이름을 입력하세요. >>");
		scanf("%s", Name);
	
		printf("전화번호를 입력하세요. >>");
		scanf("%s", PhoneNumber);
	
		strcpy(PhoneNumberBook[*count][0], Name);
		strcpy(PhoneNumberBook[*count][1], PhoneNumber);
		*count+=1;
	}
}

void del_PhoneNum(int *count, char PhoneNumberBook[MAX_NUM][2][MAX_LEN])
{
	if(*count == 0)
		printf("전호번호부가 비었습니다.\n");
	else
	{
		int num, i, j;
		printf("삭제할 번호를 입력하세요. >>");
		scanf("%d", &num); 
		
		for(i = num; i < *count+1; i++)
			for (j = 0; j < 2; j++)
				strcpy(PhoneNumberBook[i][j], PhoneNumberBook[i+1][j+1]);
		*count-=1;
	}
}

void printAll_PhoneNum(int *count, char PhoneNumberBook[MAX_NUM][2][MAX_LEN])
{
	int i, j;
	for (i = 0; i < *count; i++)
	{
		printf("%d번 ", i+1);
		for (j = 0; j < 2; j++)
			printf("%s ", PhoneNumberBook[i][j]);
		printf("\n");
	}
}

void edit_PhoneNum(int *count, char PhoneNumberBook[MAX_NUM][2][MAX_LEN])
{
	int select, num;
	char Name[20], PhoneNumber[20];
	
	printf("수정할 번호를 입력하세요. >>");
	scanf("%d", &select);
	num = select-1;
	
	if(num < 0 || num > *count)
		printf("범위 내의 번호를 입력하세요.\n");
	
	else
	{ 
	printf("이름을 입력하세요. >>");
	scanf("%s", Name);
	
	printf("전화번호를 입력하세요. >>");
	scanf("%s", PhoneNumber);
	 
	strcpy(PhoneNumberBook[num][0], Name);
	strcpy(PhoneNumberBook[num][1], PhoneNumber);
	printf("수정 되었습니다.\n");
	}
}
