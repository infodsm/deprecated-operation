#include <stdio.h>
#define MAX_NUM 10
#define MAX_LEN 20

void addPhoneNum(int *count, char ***phoneNumberBook);
void delPhoneNum(int *count, char ***phoneNumberBook);
void printAllPhoneNum(int *count, char ***phoneNumberBook);
void editPhoneNum(int *count, char ***phoneNumberBook);

int main() {
    int select = 0;
    int count = 0;
    char phoneNumberBook[MAX_NUM][2][MAX_LEN] = {0};

    while (true) {
        printf("원하는 작업을 선택하세요 (1 : 추가, 2 : 삭제, 3 : 모두 보기, 4 "
               ": 수정, 5 : 끝내기) >> \n");
        scanf("%d", &select);

        switch (select) {
        case 1:
            if (count < MAX_NUM) {
                addPhoneNum(count, phoneNumberBook);
            } else {
                printf("전화번호부가 꽉 찼습니다.\n");
            }
            break;
        case 2:
            if (count > 0) {
                delPhoneNum();
            } else {
                printf("전화번호부가 비었습니다.\n");
            }
            break;
        case 3:
            printAllPhoneNum();
            break;
        case 4:
            editPhoneNum();
            break;
        case 5:
            printf("프로그램을 종료합니다.\n");
            exit(0);
        default:
            printf("잘못 입력하셨습니다.\n");
        }
    }
}

void addPhoneNum(int *count, char ***phoneNumberBook) {
    char *name = {0};
    char *phoneNumber = {0};

    ...

        *count++;
    return;
}

void delPhoneNum(int *count, char ***phoneNumberBook) {
    int number = 0;
    int i = 0;

    printf("삭제할 번호를 입력하세요 >> ");
    scanf("%d", &number);

    for (i = number; i < MAX_NUM; i++) {
        phoneNumberBook[i - 1] = phoneNumberBook[i];
    }

    printf("삭제되었습니다.\n");
    *count--;
    return;
}

void printAllPhoneNum(int *count, char ***phoneNumberBook) {
    int i = 0;
    for (i = 0; i < count; i++) {
        printf("%s번 %s %s\n", i + 1, phoneNumberBook[i][0],
               phoneNumberBook[i][1]);
    }
    return;
}

void editPhoneNum(int *count, char ***phoneNumberBook) {}
